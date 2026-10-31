<div class="test-widget" style="background: rgba(15, 20, 25, 0.6); border-radius: 15px; padding: 1.5rem; color: #b0b0b0; border: 1px solid rgba(255, 255, 255, 0.05);">
    <h5><i class="fas fa-check-circle text-success me-2"></i>Test Widget</h5>
    <p>{{ $message }}</p>
    @if($user)
        <p>Hello, {{ $user->name_private }}!</p>
    @endif
</div>