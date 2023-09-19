Feature:
    In order to use all features of the website
    I need to register

    Scenario: I register with VALID infos
        When I send a post request to "users" with
        """
            {
                "email": "myEmail1@gmail.com",
                "pseudo": "BunBoHue"
            }
        """
        Then I should receive a success response
