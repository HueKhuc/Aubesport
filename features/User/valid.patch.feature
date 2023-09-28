@users
Feature:
    In order to modify the information of an user
    As an user
    I need to register the new information

    @test
    Scenario: I modify with VALID infos
        Given there is an existant user with email "myEmail@gmail.com" and uuid "c2ef4afb-fc3a-487d-818a-75af0a7e6816"
        When I send a patch request to "/api/users/c2ef4afb-fc3a-487d-818a-75af0a7e6816" with
        """
            {
                "pseudo": "BunBoHue"
            }
        """
        Then I should receive a status code 200
        And the node "pseudo" of the response should be "BunBoHue"
        And the node "modifiedAt" of the response should not be null
        And the field "pseudo" in the database of the user having the uuid "c2ef4afb-fc3a-487d-818a-75af0a7e6816" should be "BunBoHue"
