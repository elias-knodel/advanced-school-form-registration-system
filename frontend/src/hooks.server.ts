import type {Handle} from '@sveltejs/kit';
import {redirect} from "@sveltejs/kit";

export const handle: Handle = async ({event, resolve}) => {
    if (event.url.pathname.startsWith('/dashboard')) {
        throw redirect(302, '/login');
    }

    return resolve(event);
};
