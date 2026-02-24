# Deploy Laravel 12 + SQLite on Render

## What this repo now includes
- `Dockerfile` for Laravel 12 on PHP 8.3 (with SQLite extensions).
- `render.yaml` Blueprint for Render (`runtime: docker`).
- `scripts/render-start.sh` to:
  - create SQLite file if missing,
  - run migrations on startup,
  - start web server on `$PORT`.

## Deploy steps
1. Push this repo to GitHub/GitLab.
2. In Render, create a new service from Blueprint and select this repo.
3. Keep plan at least `starter` (Persistent Disk is required for SQLite persistence).
4. Set `APP_URL` to your Render public URL (for example `https://your-service.onrender.com`).
5. Deploy.

## Important notes
- SQLite data is stored at `/var/data/database.sqlite` (Persistent Disk mount path).
- Every new deploy/start runs `php artisan migrate --force`.
- If you switch to free plan/no disk, SQLite data will be lost on restart/redeploy.
