{{ header }}

<table width="100%">
    <tbody>
        <tr>
            <td class="wrapper" width="700" align="center">
                <table class="section" cellpadding="0" cellspacing="0" width="700" bgcolor="#f8f8f8">
                    <tr>
                        <td class="column" align="left">
                            <table>
                                <tbody>
                                <tr>
                                    <td align="left" style="padding: 20px 50px;">
                                        <p><strong>Hello, there is a new booking from {{ site_title }}:</strong></p>
                                        <p><img src="{{ site_url }}/vendor/core/images/emails/person.png"
                                                alt="From" width="20" style="margin-right: 10px;" /> {{ booking_name }}</p>
                                        <p><img src="{{ site_url }}/vendor/core/images/emails/email.png"
                                                alt="Email" width="20" style="margin-right: 10px;" /> {{ booking_email }}</p>
                                        <p><img src="{{ site_url }}/vendor/core/images/emails/phone.png"
                                                alt="Phone" width="20" style="margin-right: 10px;" /> {{ booking_phone }}</p>
                                        <p><img src="{{ site_url }}/vendor/core/images/emails/message.png"
                                                alt="Message" width="20" style="margin-right: 10px;" /> {{ booking_address }}</p>
                                        <p>View booking detail: {{ booking_link }}</p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
{{ footer }}
