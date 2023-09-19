Feature:
    In order to avoid invalid information
    As a developer 
    I want the application validate infos before saving into the database

    Scenario: Players register with invalid email
        When I send a post request to "/api/users" with
        """
            {
                "email": "myEmail1",
                "pseudo": "BunBoHue"
            }
        """
        Then I should receive a status code 400
    
    Scenario: Players register with existing email
        Given a user with email "myEmail@gmail.com"
        When I send a post request to "/api/users" with
        """
            {
                "email": "myEmail@gmail.com",
                "pseudo": "BunBoHue"
            }
        """
        Then I should receive a status code 409
