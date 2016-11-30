# VirtEngine WHMCS Hooks

## 1. Upload Hooks

Go to whmcsroot/includes/ and perform the following.

```

cd /var/www/html/whmcs/

git clone https://gitlab.com/megamsys/whmcs_hooks

cp whmcs_hooks ./Hooks

rm -rf whmcs_hooks

```

## 2. Create the following Client Custom Fields.

1. vertice_email (Field Type => Text Box)
2. organization_id (Field Type => Text Box)

For creating the custom fields goto
`Setup -> Client Custom Fields ` in whmcsroot

## 3. Edit virtengine_api.php

- Update the MASTERKEY from /var/lib/virtengine/virtenginegateway/gateway.conf
- Update the API server (Gateway)
