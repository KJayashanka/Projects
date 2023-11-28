<!DOCTYPE html>
<html>
<head>
    <title>Merged Invoices</title>
</head>
<body>
    @foreach($pdfs as $pdf)
        <div style="page-break-before: always;"></div>
        {!! $pdf->output() !!}
    @endforeach
</body>
</html>
