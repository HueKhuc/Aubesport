@addresses
Feature:
    In order to modify the information of an user
    As an user
    I need to register the new information

    Scenario: I modify with VALID infos
        Given there is an existant user with email "my-Email@gmail.com" and uuid "c2ef4afb-fc3a-487d-818a-75af0a7e6819"
        When I send a post request to "/api/users/c2ef4afb-fc3a-487d-818a-75af0a7e6819/addresses" with
        """
            {
                 "streetName": "rue garnier",
                 "streetNumber": "10",
                 "city": "Lyon",
                 "postalCode":"69008"
            }
        """
        Then I should receive a status code 201