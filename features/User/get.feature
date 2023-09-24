@users
Feature:
    In order to get the information of users 
    As a developer 
    I need to send a get request

    Scenario: Send a request with query parameters: the current page is the last page
        Given there are 11 users in the database
        When I send a get request to "/api/users?currentPage=3&elementsPerPage=5"
        Then I should receive a status code 200
        And the node "totalOfPages" of the reponse should be 3
        And the node "elementsPerPage" of the reponse should be 5
        And the node "nextPage" of the reponse should be NULL

    Scenario: Send a request with query parameters: the current page is the first page
        Given there are 11 users in the database
        When I send a get request to "/api/users?currentPage=1&elementsPerPage=5"
        Then I should receive a status code 200
        And the node "totalOfPages" of the reponse should be 3
        And the node "elementsPerPage" of the reponse should be 5
        And the node "previousPage" of the reponse should be NULL
    
    Scenario: Send a request with query parameters: the elements per page is too large
        Given there are 11 users in the database
        When I send a get request to "/api/users?currentPage=1&elementsPerPage=10000"
        Then I should receive a status code 200
        And the node "totalOfPages" of the reponse should be 2
        And the node "elementsPerPage" of the reponse should be 10
        And the node "previousPage" of the reponse should be NULL

    Scenario: Send a request without query parameters
        Given there are 11 users in the database
        When I send a get request to "/api/users"
        Then I should receive a status code 200
        And the node "totalOfPages" of the reponse should be 2
        And the node "elementsPerPage" of the reponse should be 10
        And the node "previousPage" of the reponse should be NULL
