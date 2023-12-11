// Wait for the document to be fully loaded
$(document).ready(function() {
    // Attach a click event handler to the search button
    $("#search-button").click(function() {
        performSearch();
    });

    // Attach a key press event listener to the search input
    $("#search-input").keypress(function(event) {
        // Check if the key pressed is Enter (key code 13)
        if (event.which == 13) {
            performSearch();
            event.preventDefault(); // Prevent form submission
        }
    });

    function performSearch() {
        // Get the search query from the input field
        var searchQuery = $("#search-input").val();
    
        // Check if the search query is empty or only consists of whitespace
        if (!searchQuery.trim()) {
            alert("Please enter a StudentID before searching!");
            return; // Exit the function early if the input is empty
        }
    
        // Send an AJAX request to search.php
        $.ajax({
            url: "Search.php",
            method: "GET",
            data: { search: searchQuery }, // Pass the search query as a parameter
            success: function(response) {
                if (response === 'No results found.') {
                    alert(response);
                } else {
                    $("#search-results").html(response);
                }
            }
        });
    }
});
