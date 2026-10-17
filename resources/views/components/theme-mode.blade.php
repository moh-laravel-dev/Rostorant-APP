<script>
    (() => {
        const storageKey = 'restaurant-theme';
        const root = document.documentElement;
        const themeColor = document.querySelector('meta[name="theme-color"]');
        const lightThemeColor = themeColor?.content || '#174c3c';

        const readTheme = () => {
            try {
                const theme = localStorage.getItem(storageKey);
                return theme === 'dark' || theme === 'light' ? theme : 'light';
            } catch (error) {
                console.warn('Could not read the saved theme preference.', error);
                return 'light';
            }
        };

        const applyTheme = (theme) => {
            root.dataset.theme = theme;
            root.style.colorScheme = theme;

            if (themeColor) {
                themeColor.content = theme === 'dark' ? '#121a16' : lightThemeColor;
            }
        };

        const updateButton = (button, theme) => {
            button.textContent = theme === 'dark' ? '☀' : '☾';
            button.setAttribute('aria-label', theme === 'dark' ?
                'التبديل إلى الوضع الفاتح' :
                'التبديل إلى الوضع الداكن');
            button.setAttribute('title', theme === 'dark' ?
                'التبديل إلى الوضع الفاتح' :
                'التبديل إلى الوضع الداكن');
            button.setAttribute('aria-pressed', String(theme === 'dark'));
        };

        applyTheme(readTheme());

        const addToggle = () => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'theme-toggle';
            button.setAttribute('data-theme-toggle', '');
            updateButton(button, root.dataset.theme);
            button.addEventListener('click', () => {
                const theme = root.dataset.theme === 'dark' ? 'light' : 'dark';
                applyTheme(theme);
                updateButton(button, theme);

                try {
                    localStorage.setItem(storageKey, theme);
                } catch (error) {
                    console.warn('Could not save the theme preference.', error);
                }
            });
            document.body.appendChild(button);
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', addToggle, { once: true });
        } else {
            addToggle();
        }
    })();
</script>
