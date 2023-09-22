Feature:
    In order to read API documentation 
    As a developer 
    I need to access to the website

    Scenario: I access to the right uri of the page
        When I send a get request to "/api/doc" of the API documentation's page
        Then I should receive a status code 200
