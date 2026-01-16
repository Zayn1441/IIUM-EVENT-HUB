const Auth = {
    user: null,

    async init() {
        // Setup Routes
        Router.add('#/login', () => Router.loadView('login').then(() => this.bindLogin()));
        Router.add('#/register', () => Router.loadView('register').then(() => this.bindRegister()));
        Router.add('#/', () => {
            if (this.user) {
                // If logged in, go dashboard
                Router.navigate('#/dashboard');
            } else {
                // Else go login
                Router.navigate('#/login');
            }
        });
        Router.add('#/dashboard', () => {
            if (!this.user) {
                Router.navigate('#/login');
                return;
            }
            Router.loadView('dashboard').then(() => Dashboard.init());
        });

        Router.add('#/events/create', () => {
            Router.loadView('events/create').then(() => Events.initCreate());
        });

        Router.add('#/events/:id', (id) => {
            Router.loadView('events/show').then(() => Events.initShow(id));
        });

        Router.add('#/events/:id/edit', (id) => {
            Router.loadView('events/edit').then(() => Events.initEdit(id));
        });

        Router.add('#/profile', () => {
            Router.loadView('profile/edit').then(() => Profile.init());
        });

        Router.add('#/notices', () => {
            Router.loadView('notices/index').then(() => Notices.init());
        });

        Router.add('#/my-events', () => {
            Router.loadView('events/my').then(() => Events.initMyEvents());
        });


        // Check session (We assume session cookie works, maybe hit an endpoint to get user info?)
        // For now, let's assume we need to re-login or store user in localStorage for SPA feel,
        // BUT reliable way is asking backend.
        // Let's rely on localStorage for "UI" state, but verify with API if critical.

        const storedUser = localStorage.getItem('user');
        if (storedUser) {
            this.user = JSON.parse(storedUser);
        }

        Router.init();
    },

    bindLogin() {
        const form = document.querySelector('form');
        if (!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const btn = form.querySelector('button');

            try {
                btn.disabled = true;
                btn.innerText = 'Logging in...';

                const response = await App.request('auth/login.php', 'POST', { email, password });

                this.user = response.user;
                localStorage.setItem('user', JSON.stringify(this.user));
                App.showToast('Login Successful');
                Router.navigate('#/dashboard');
            } catch (error) {
                const msg = error.errors ? Object.values(error.errors)[0] : (error.message || 'Login failed');
                App.showToast(msg, 'error');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Login';
            }
        });
    },

    bindRegister() {
        const form = document.querySelector('form');
        if (!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            // similar logic to login...
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries()); // Simple for text inputs

            // Password confirmation check client side or rely on backend

            try {
                const response = await App.request('auth/register.php', 'POST', data);
                this.user = response.user;
                localStorage.setItem('user', JSON.stringify(this.user));
                App.showToast('Registration Successful');
                Router.navigate('#/dashboard');
            } catch (error) {
                const msg = error.errors ? Object.values(error.errors)[0] : (error.message || 'Registration failed');
                App.showToast(msg, 'error');
            }
        });
    },

    logout() {
        App.request('auth/logout.php', 'POST').then(() => {
            this.user = null;
            localStorage.removeItem('user');
            Router.navigate('#/login');
            App.showToast('Logged out');
        });
    }
};
