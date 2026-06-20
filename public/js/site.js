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
        var csrfMeta = document.querySelector('meta[name="csrf-token"]');
        var csrf = csrfMeta ? csrfMeta.content : '';

        if (!panel || !toggle) {
            return;
        }

        chat.dataset.liveChatReady = 'true';

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
        });

        if (close) {
            close.addEventListener('click', function () {
                panel.classList.add('d-none');
            });
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
                    form.reset();
                    showMessage(success, data.message || 'Message sent.');
                    restoreSubmit();
                })
                .catch(function (exception) {
                    showMessage(error, exception.message || 'Could not send your message.');
                    restoreSubmit();
                });
        });
    });
})();
