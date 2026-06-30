import { useDataLoader, useDeleteConfirmation } from '../mixins.js';

export function usersData() {
    return {
        ...useDataLoader('/users', 'users'),
        ...useDeleteConfirmation((user) => `/users/${user.public_id}`),

        async init() {
            await this.loadData();
        },
    };
}
