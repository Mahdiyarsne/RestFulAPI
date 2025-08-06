Hello {{$user->name}}
Thank you for your email,Please verify your new  email address using this link below: 
{{route('verify',$user->verification_token)}}