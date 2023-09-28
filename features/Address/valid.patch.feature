@addresses
Feature:
    In order to modify the information of an address
    As an user
    I need to register the new information

    @test
    Scenario: I modify with VALID infos
        Given there is an existent user with an email "myEmail@gmail.com", uuid "c2ef4afb-fc3a-487d-818a-75af0a7e6816", and an address with streetName "rue nicolas", streetNumber "15", city "Lyon", postalCode "69008"
        When I send a patch request to "/api/users/c2ef4afb-fc3a-487d-818a-75af0a7e6816/addresses" with
        """
            {
                 "streetName": "rue jean jaures"
            }
        """
        Then I should receive a status code 200
