@if (!empty($error))
    <div class="alert alert-danger">
        {{{$error}}}
    </div>
@end
{{$form->open()}}
    {{$form->text("email")}}
    {{$form->password("password")}}
    <input type="submit" value="login" />
{{$form->Close()}}
