provider "openstack" {
    # Your user account.
    user_name = "munin@list.ru"
    
    # The password of the account
    password = "YOUR_PASSWORD"
    
    # The tenant token can be taken from the project Settings tab - > API keys.
    # Project ID will be our token.
    tenant_id = "b7a18cffb45f429e84d8c180da12758d"
    
    # The indicator of the location of users.
    user_domain_id = "users"
    
    # API endpoint
    # Terraform will use this address to access the MCS api.
    auth_url = "https://infra.mail.ru/identity/v3/"
    
    # use octavia to manage load balancers
    use_octavia = true
}
