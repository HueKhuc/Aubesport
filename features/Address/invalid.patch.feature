@addresses
Feature:
    In order to modify the address of an user
    As an user
    I need to register the new information

    Scenario: I modify with INVALID postalCode
        Given there is an existant user with email "myEmail@gmail.com", uuid "c2ef4afb-fc3a-487d-818a-75af0a7e6816", address with streetNumber "14", streetName "Rue de la République", postalCode "69002" and city "Lyon"
        When I send a patch request to "/api/users/c2ef4afb-fc3a-487d-818a-75af0a7e6816/addresses" with
        """
            {
                "postalCode": "692000"
            }
        """
        Then I should receive a status code 422
        And the node "detail" of the response should be "postalCode: Le code postal doit contenir exactement 5 chiffres."
