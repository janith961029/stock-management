import preset from './vendor/filament/support/tailwind.config.preset'
import { createRequire } from 'module';

const require = createRequire(import.meta.url);

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
};
