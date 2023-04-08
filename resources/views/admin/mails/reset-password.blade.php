<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h2>{{$details['title']}}</h2>
    <p>Xin chào {{$details['receiver']}}</p>
    <p>{{$details['body']}}</p>
    <a href="{{route('reset-password',['token' => $details['token']])}}">Thay đổi mật khẩu</a>
    {{-- @if (!empty($details['body1']))
        <p>{{$details['body1']}}</p>
    @endif
    @if (!empty($details['body2']))
        <p>{{$details['body2']}}</p>
    @endif
    @if (!empty($details['reason']))
        <p>{{$details['reason']}}</p>
    @endif
    @if (empty($details['contact']))
        <p>Nếu bạn có thắc mắc, vui lòng liên hệ qua email: admin@gmail.com</p> 
    @else
        <p>Nếu bạn có thắc mắc, vui lòng liên hệ qua email: {{$details['contact']}}</p>
    @endif
    <p>Trân thành cảm ơn</p> --}}
</body>
</html>