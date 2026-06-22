// Customization point: add small storefront interactions here without compiling assets.
(function () {
    function onReady(callback) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', callback);
            return;
        }

        callback();
    }

    onReady(function () {
        document.querySelectorAll('[data-confirm]').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!window.confirm(form.dataset.confirm)) {
                    event.preventDefault();
                }
            });
        });

        var chat = document.querySelector('[data-live-chat]');

        if (!chat) {
            return;
        }

        var panel = chat.querySelector('[data-live-chat-panel]');
        var toggle = chat.querySelector('[data-live-chat-toggle]');
        var close = chat.querySelector('[data-live-chat-close]');
        var form = chat.querySelector('[data-live-chat-form]');
        var submit = chat.querySelector('[data-live-chat-submit]');
        var success = chat.querySelector('[data-live-chat-success]');
        var error = chat.querySelector('[data-live-chat-error]');
        var messagesBox = chat.querySelector('[data-live-chat-messages]');
        var conversationInput = chat.querySelector('[data-live-chat-conversation]');
        var csrfMeta = document.querySelector('meta[name="csrf-token"]');
        var csrf = csrfMeta ? csrfMeta.content : '';
        var messagesUrl = chat.dataset.messagesUrl || '';
        var storageKey = 'shopsphere_live_chat_conversation';
        var lastMessageId = 0;
        var pollTimer = null;

        if (!panel || !toggle) {
            return;
        }

        function showMessage(element, message) {
            if (!element) {
                return;
            }

            element.textContent = message;
            element.classList.remove('d-none');
        }

        function hideMessages() {
            if (success) {
                success.classList.add('d-none');
            }

            if (error) {
                error.classList.add('d-none');
            }
        }

        function getConversationId() {
            var existing = window.localStorage ? window.localStorage.getItem(storageKey) : '';

            if (existing) {
                return existing;
            }

            var generated = 'guest-' + Date.now().toString(36) + '-' + Math.random().toString(36).slice(2, 12);

            if (window.localStorage) {
                window.localStorage.setItem(storageKey, generated);
            }

            return generated;
        }

        function appendChatMessage(message) {
            if (!messagesBox || !message || !message.id || message.id <= lastMessageId) {
                return;
            }

            var empty = messagesBox.querySelector('.live-chat-empty');

            if (empty) {
                empty.remove();
            }

            var bubble = document.createElement('div');
            var senderType = message.sender_type === 'admin' ? 'admin' : 'customer';
            var text = document.createElement('div');
            var meta = document.createElement('span');

            bubble.className = 'live-chat-bubble live-chat-bubble-' + senderType;
            text.textContent = message.message || '';
            meta.className = 'live-chat-meta';
            meta.textContent = (senderType === 'admin' ? 'Admin' : 'You') + (message.display_time ? ' · ' + message.display_time : '');

            bubble.appendChild(text);
            bubble.appendChild(meta);
            messagesBox.appendChild(bubble);
            messagesBox.scrollTop = messagesBox.scrollHeight;
            lastMessageId = Math.max(lastMessageId, message.id);
        }

        function pollMessages() {
            if (!messagesUrl || !window.fetch || !conversationInput || !conversationInput.value) {
                return;
            }

            var url = messagesUrl + '?conversation_id=' + encodeURIComponent(conversationInput.value) + '&after_id=' + encodeURIComponent(lastMessageId);

            fetch(url, {
                headers: {
                    Accept: 'application/json'
                }
            })
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('Could not load chat messages.');
                    }

                    return response.json();
                })
                .then(function (data) {
                    (data.messages || []).forEach(appendChatMessage);
                })
                .catch(function () {
                    window.clearInterval(pollTimer);
                    pollTimer = null;
                });
        }

        function startPolling() {
            if (pollTimer || !conversationInput || !conversationInput.value) {
                return;
            }

            pollMessages();
            pollTimer = window.setInterval(pollMessages, 5000);
        }

        function getValidationMessage(data) {
            var messages = [];
            var errors = data && data.errors ? data.errors : {};

            Object.keys(errors).forEach(function (field) {
                if (Array.isArray(errors[field])) {
                    messages = messages.concat(errors[field]);
                }
            });

            return (data && data.message) || messages.join(' ');
        }

        toggle.addEventListener('click', function () {
            panel.classList.toggle('d-none');
            hideMessages();

            if (!panel.classList.contains('d-none')) {
                startPolling();
            }
        });

        if (close) {
            close.addEventListener('click', function () {
                panel.classList.add('d-none');
            });
        }

        if (conversationInput) {
            conversationInput.value = getConversationId();
        }

        if (!form || !submit || !window.fetch || !window.FormData) {
            return;
        }

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            hideMessages();
            submit.disabled = true;
            submit.textContent = 'Sending...';

            function restoreSubmit() {
                submit.disabled = false;
                submit.textContent = 'Send Message';
            }

            fetch(form.action, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: new FormData(form)
            })
                .then(function (response) {
                    return response.json().then(function (data) {
                        if (!response.ok) {
                            throw new Error(getValidationMessage(data) || 'Could not send your message.');
                        }

                        return data;
                    });
                })
                .then(function (data) {
                    var messageInput = form.querySelector('[name="message"]');

                    if (data.conversation_id && conversationInput) {
                        conversationInput.value = data.conversation_id;

                        if (window.localStorage) {
                            window.localStorage.setItem(storageKey, data.conversation_id);
                        }
                    }

                    appendChatMessage(data.data);

                    if (messageInput) {
                        messageInput.value = '';
                    }

                    showMessage(success, data.message || 'Message sent.');
                    restoreSubmit();
                    startPolling();
                })
                .catch(function (exception) {
                    showMessage(error, exception.message || 'Could not send your message.');
                    restoreSubmit();
                });
        });
    });
})();
