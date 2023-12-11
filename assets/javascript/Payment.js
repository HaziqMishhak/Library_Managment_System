
$(document).ready(function() {
    $('#searchBtn').on('click', function() {
        var studentId = $('#studentIdSearch').val();
        if (!studentId) {
            // Display error if no Student ID entered
            alert('Please enter a Student ID.');
            return;
        }

        $.ajax({
            url: '../Action/get_fine.php',  // Updated to the correct path assuming this is in the same directory level
            type: 'POST',
            data: { studentId: studentId },
            dataType: 'json',
            success: function(data) {
                var totalAmountDue = 0;
                var resultsBody = $('#searchResults');
                resultsBody.empty(); // Clear previous results

                if (data.length === 0) {
                    // If no records are found, display a message
                    resultsBody.append($('<tr/>').append($('<td/>', {
                        colspan: 4,
                        text: 'No pending fines found for this Student ID.'
                    })));
                    $('#totalAmountDue').text('0.00');
                    return;
                }

                // If records are found, process and display them
                $.each(data, function(i, fine) {
                    totalAmountDue += parseFloat(fine.AmountDue);
                    var row = $('<tr/>');
                    row.append($('<td/>').text(fine.StudentID));
                    row.append($('<td/>').text(fine.BookName));
                    row.append($('<td/>').text(fine.ReturnDate));
                    row.append($('<td/>').text(fine.AmountDue));
                    resultsBody.append(row);
                });

                // Update Total Amount Due
                $('#totalAmountDue').text(totalAmountDue.toFixed(2));
            },
            error: function(xhr, status, error) {
                console.error("An error occurred: " + error);
                alert("An error occurred: " + xhr.responseText);
            }
        });
    });

    // Submit payment when the payment form is submitted
    $('#paymentForm').on('submit', function(e) {
        e.preventDefault();

        // FormData to handle file uploads
        var formData = new FormData(this);
        formData.append('studentId', $('#studentIdSearch').val()); // Assuming this is the ID of the input field with the student ID
        formData.append('totalAmount', $('#totalAmountDue').text()); // Get the total amount calculated on the page

        // Append the number of books if you're keeping track of that
        var numberOfBooks = $('#searchResults tr').length; // This assumes each row in the searchResults table is a book/fine
        formData.append('numberOfBooks', numberOfBooks);

        $.ajax({
            url: '../Action/process_payment.php',
            type: 'POST',
            data: formData,
            contentType: false, // Required for FormData with file upload
            processData: false, // Required for FormData with file upload
            dataType: 'json', // Expect a JSON response
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    // Here you can clear the search results or refresh the page as needed
                    $('#paymentForm').trigger('reset');
                    $('#searchResults').empty();
                    $('#totalAmountDue').text('0.00');
                    $('#insertdata').modal('hide');
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("An error occurred: " + error);
                alert("An error occurred: " + xhr.responseText);
            }
        });
    });
});



