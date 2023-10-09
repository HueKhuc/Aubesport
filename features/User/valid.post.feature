@users
Feature:
    In order to use all features of the website
    As a player
    I need to register

    Scenario: I register with VALID infos
        When I send a post request to "/api/users" with
        """
            {
                "email": "myEmail15@gmail.com",
                "pseudo": "BunBoHue",
                "password": "Ab1234567?"
            }
        """
        Then I should receive a status code 201
