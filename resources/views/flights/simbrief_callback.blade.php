<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Processing SimBrief Flight Plan</title>
    <script type="text/javascript">
        (function() {
            var briefingUrl = '{{ $briefing_url }}';
            
            // If we're in a popup (opened by window.open), redirect the opener and close
            if (window.opener && !window.opener.closed) {
                try {
                    // Try to send a message first (in case direct redirect is blocked)
                    window.opener.postMessage({
                        type: 'simbrief_complete',
                        briefingUrl: briefingUrl
                    }, window.location.origin);
                    
                    // Also try direct redirect
                    window.opener.location.href = briefingUrl;
                    window.close();
                    return;
                } catch (e) {
                    console.error('Could not redirect opener:', e);
                    // Fall through to direct redirect
                }
            }
            
            // If we're in an iframe, try to redirect parent
            try {
                if (window.parent && window.parent !== window) {
                    window.parent.location.href = briefingUrl;
                    return;
                }
            } catch (e) {
                // Cross-origin - can't access parent
            }
            
            // Direct redirect if not in popup or iframe
            window.location.href = briefingUrl;
        })();
    </script>
</head>
<body>
    <p>Processing flight plan... Redirecting...</p>
    <noscript>
        <meta http-equiv="refresh" content="0;url={{ $briefing_url }}">
        <p>If you are not redirected, <a href="{{ $briefing_url }}">click here</a>.</p>
    </noscript>
    <script type="text/javascript">
        // Fallback redirect after 500ms
        setTimeout(function() {
            var briefingUrl = '{{ $briefing_url }}';
            
            // Try opener first (popup scenario)
            try {
                if (window.opener && !window.opener.closed) {
                    window.opener.location.href = briefingUrl;
                    window.close();
                    return;
                }
            } catch (e) {
                // Can't access opener
            }
            
            // Fallback to direct redirect
            try {
                window.location.href = briefingUrl;
            } catch (e) {
                console.error('Could not redirect:', e);
            }
        }, 500);
    </script>
</body>
</html>

