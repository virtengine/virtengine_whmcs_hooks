# VirtEngine WHMCS Hooks

## 1. Upload Hooks

Go to whmcsroot/includes/ and perform the following.

```

cd /var/www/html/whmcs/includes

git clone https://gitlab.com/megamsys/whmcs_hooks

cp -r whmcs_hooks ./hooks

rm -rf whmcs_hooks

```

(or) Download the latest package from google drive contact hello@virtengine.com

## 2. Create the following Client Custom Fields.

1. email (Field Type => Text Box)
2. org_id (Field Type => Text Box)

For creating the custom fields goto
`Setup -> Client Custom Fields ` in whmcsroot

## 3. Edit virtengine_api.php

- Update the MASTERKEY from /var/lib/virtengine/virtenginegateway/gateway.conf
- Update the API server (Gateway)
