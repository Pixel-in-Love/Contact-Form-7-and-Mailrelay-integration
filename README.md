# Contact Form 7 and Mailrelay integration

This plugin integrates Contact Form 7 and Mailrelay, so when a visitor submits a form (and provided he has checked the acceptance checkbox), a contact his name and email address will be added to the specified list.

In order to configure it, once installed and activated, you need to go to Settings / Mailrelay API Integration and provide the following information:

- Mailrelay API key.
- ID of the list where you want to add the contact.
- The URL of your Mailrelay instance (i.e. *my-instance.ipzmarketing.com*).

**Important**: In order to make the integration work, your contact forms must include the following fields:

- An email field with the id "your-email"
- A text field with the id "your-name"
- An acceptance field with the id "acceptance"

[Download the plugin](#)