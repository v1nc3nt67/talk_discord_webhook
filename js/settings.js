document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('talk-discord-webhook-form');
    const list = document.getElementById('talk-discord-webhook-list');

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const name = document.getElementById('webhook-name').value;
            const roomToken = document.getElementById('webhook-room-token').value;
            const url = OC.generateUrl('/apps/talk_discord_webhook/settings');

            const data = {
                name: name,
                roomToken: roomToken
            };

            const options = {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'requesttoken': OC.requestToken
                },
                body: JSON.stringify(data)
            };

            fetch(url, options)
                .then(response => response.json())
                .then(data => {
                    if (data.id) {
                        // Reload page to show new webhook simpler than building DOM
                        location.reload();
                    } else {
                        alert('Error creating webhook');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error creating webhook');
                });
        });
    }

    // Handle delete
    if (list) {
        list.addEventListener('click', function (e) {
            if (e.target.classList.contains('icon-delete')) {
                const item = e.target.closest('.webhook-item');
                const id = item.dataset.id;
                const url = OC.generateUrl('/apps/talk_discord_webhook/settings/' + id);

                if (confirm('Are you sure you want to delete this webhook?')) {
                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'requesttoken': OC.requestToken
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                item.remove();
                            } else {
                                alert('Error deleting webhook');
                            }
                        });
                }
            }
        });
    }
});
