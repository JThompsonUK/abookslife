<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Email</title>
</head>
<body>
    <table style="width: 100%; max-width: 600px; margin: 0 auto; padding: 20px;">
        <tr>
            <td style="text-align: center; padding-bottom: 20px;">
                <!-- <img src="{{ asset('path/to/your/logo.png') }}" alt="Logo" style="max-width: 200px;"> -->
            </td>
        </tr>
        <tr>
            <td>
                <h2>{{ $book }}</h2>
                <p>Dear {{ $owner }},</p>
                <p>Someone has checked out your book!</p>
                <a href="{{ url('/books/' . $uuid) }}" 
                    style="color: #fff;
                    background-color: #17a2b8;
                    border: 1px solid #17a2b8;
                    padding: 0.375rem 0.75rem;
                    border-radius: 0.5rem;
                    text-align: center;
                    text-decoration: none;
                ">
                    View your book                
                </a>             
            </td>
        </tr>
    </table>
</body>
</html>
