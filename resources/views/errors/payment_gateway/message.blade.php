<!doctype html>
<title>{{ config('app.name', 'Laravel') }}</title>
<style>
  body { text-align: center; padding: 150px; }
  h1 { font-size: 50px; }
  body { font: 20px Helvetica, sans-serif; color: #333; }
  article { display: block; text-align: left; width: 650px; margin: 0 auto; }
  a { color: #dc8100; text-decoration: none; }
  a:hover { color: #333; text-decoration: none; }
</style>

<article>
    <h1>Information!</h1>
    <div>
        <p>
            @php echo $message; @endphp
        </p>
        <p>&mdash; {{ config('app.name', 'Laravel') }}</p>
    </div>
</article>