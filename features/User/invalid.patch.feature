@users
Feature:
    In order to avoid invalid information
    As a developer 
    I want the application validate infos before saving into the database

    Scenario: Players register with invalid gender
        Given there is an existant user with email "myEmail@gmail.com" and uuid "a245f890-accf-4295-a00a-522732682fdc"
        When I send a patch request to "/api/users/a245f890-accf-4295-a00a-522732682fdc" with
        """
            {
                "gender": "trans"
            }
        """
        Then I should receive a status code 422
        And the node "detail" of the response should be "gender: Choose a valid gender."

    Scenario: Players register with invalid firstName
        Given there is an existant user with email "myEmail@gmail.com" and uuid "a245f890-accf-4295-a00a-522732682fdc"
        When I send a patch request to "/api/users/a245f890-accf-4295-a00a-522732682fdc" with
        """
            {
                "firstName": "@@@@"
            }
        """
        Then I should receive a status code 422
        And the node "detail" of the response should be "firstName: This value is not valid."
