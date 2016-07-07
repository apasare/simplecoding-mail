# SimpleCoding_Mail
A Magento2 module which adds SMTP transport.

### Installation (using composer)
Update your Magento2 project's **composer.json** as follow:

    ...
    "require": {
        ...
        "godvsdeity/simplecoding-mail": "0.1.0"
    },
    ...
    "repositories": [
        ...
        {
            "type": "vcs",
            "url": "git@github.com:godvsdeity/simplecoding-mail.git"
        }
    ],
    ...

And then run `composer update`.

### Settings
You can configure setup your smtp account from: **STORES** -> **Configuration** -> **SIMPLECODING** -> **Mail Settings** -> **SMTP Settings**.
