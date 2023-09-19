Feature:
    In order to use all features of the website
    As a player
    I need to register

    Scenario: I register with VALID infos
        When I send a post request to "/api/users" with
        """
            {
                "email": "myEmail1@gmail.com",
                "pseudo": "BunBoHue"
            }
        """
        Then I should receive a status code 201
