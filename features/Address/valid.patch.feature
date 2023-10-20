@address
Feature:
    In order to modify the address of an user
    As an user
    I need to register the new information

    Scenario: I modify with VALID infos
        Given there is an existant user with email "myEmail@gmail.com", uuid "c2ef4afb-fc3a-487d-818a-75af0a7e6816", address with streetNumber "14", streetName "Rue de la République", postalCode "69002" and city "Lyon"
        When I send a patch request to "/api/users/c2ef4afb-fc3a-487d-818a-75af0a7e6816/address" with
        """
            {
                "streetNumber": "16C"
            }
        """
        Then I should receive a status code 200
        And the node "streetNumber" of the response should be "16C"
