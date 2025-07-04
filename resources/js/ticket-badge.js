function updateTicketBadge() {
    fetch('/api/tickets/new-count')
        .then(response => response.json())
        .then(data => {
            document.getElementById('ticket-badge').textContent = data.count;
        });
}

// Update every 30 seconds
setInterval(updateTicketBadge, 30000);
updateTicketBadge();