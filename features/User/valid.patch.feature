@users
Feature:
    In order to modify the information of an user
    As an user
    I need to register the new information

    @test
    Scenario: I modify with VALID infos
        Given there is an existant user with email "myEmail@gmail.com" and uuid "a245f890-accf-4295-a00a-522732682fdc"
        When I send a patch request to "/api/users/a245f890-accf-4295-a00a-522732682fdc" with
        """
            {
                "pseudo": "BunBoHue"
            }
        """
        Then I should receive a status code 200
        And the node "pseudo" of the reponse should be "BunBoHue"
