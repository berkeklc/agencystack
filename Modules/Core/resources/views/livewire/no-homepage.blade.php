<div style="min-height:100vh; display:grid; place-items:center; text-align:center; padding:2rem; background:var(--color-bg);">
    <div style="max-width:560px;">
        <div style="font-size:4rem; margin-bottom:1.5rem;" aria-hidden="true">🚀</div>
        <h1 style="font-size:clamp(1.75rem, 4vw, 2.5rem); color:var(--color-primary); margin:0 0 1rem;">
            AgencyStack is installed!
        </h1>
        <p style="font-size:1.0625rem; color:var(--color-muted); line-height:1.7; margin:0 0 2rem;">
            No homepage has been set yet. Go to the admin panel, create a page, and toggle
            <strong>"Set as homepage"</strong> to make it appear here.
        </p>
        <div style="display:flex; gap:1rem; justify-content:center; flex-wrap:wrap;">
            <a href="/admin/pages/create" class="btn-primary">Create homepage</a>
            <a href="/admin" class="btn-ghost">Go to admin panel</a>
        </div>
        <p style="margin-top:2rem; font-size:0.875rem; color:var(--color-muted); opacity:0.6;">
            Or run <code style="background:var(--color-surface); padding:0.2em 0.5em; border-radius:4px; font-size:0.9em;">php artisan agency:install</code> to set up default content.
        </p>
    </div>
</div>
