<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Maruti Developers</title>
    <meta content="width=device-width, " name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@600&family=Lobster+Two:wght@700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
<body>
<div class="container-xxl bg-white p-0">
    <X-mail::message>
        Hello ! you got an enquiry !
    
        <h1 style="font-family:'Times New Roman', Times, serif ;">Hello ! {{ $mailData['employer']->name }}</h1>
        <p>Job Title : {{ $mailData['job']->title }}</p>

        <p>Employee Detail  :   </p>
        <p>Name :{{ $mailData['user']->name }}</p>
        <p>Email :{{ $mailData['user']->email }}</p>
        <p>Mobile No :{{ $mailData['user']->mobile }}</p>
        <footer>  <p> Thanks ! For Visit Again..!</p></footer>
    </X-mail::message>
    </div>
</body>
</html>