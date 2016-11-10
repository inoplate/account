<h3> Account email change confirmation </h3>

To confirm your new email address click this <a href="{{ route('account.auth.email.reset', ['token' => $reset->token]) }}">link</a>