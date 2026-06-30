export function useFormSubmission({ url, method = 'POST', successMessage = 'Salvo com sucesso!', redirectTo = null }) {
    return {
        form: {},
        errors: {},
        loading: false,

        parseErrors(data, status) {
            if (status === 422 && data.errors) {
                this.errors = Object.fromEntries(
                    Object.entries(data.errors).map(([field, messages]) => [field, messages[0]])
                );
            } else {
                this.errors = { general: data.message || 'Erro ao salvar.' };
            }
        },

        resetErrors() {
            this.errors = {};
        },

        async submit(payload = null) {
            this.resetErrors();
            this.loading = true;

            const { success, data, status } = await window.api(url, {
                method,
                body: JSON.stringify(payload || this.form),
            });

            this.loading = false;

            if (success) {
                window.showAlert(successMessage, 'success');
                if (redirectTo) window.location.href = redirectTo;
            } else {
                this.parseErrors(data, status);
            }

            return { success, data, status };
        },
    };
}

export function useDataLoader(url, propertyName = 'items') {
    return {
        [propertyName]: [],
        loading: false,

        async loadData() {
            this.loading = true;
            const { success, data } = await window.api(url);
            if (success) this[propertyName] = data;
            this.loading = false;
        },
    };
}

export function useDeleteConfirmation(urlBuilder, successMessage = 'Excluído com sucesso!') {
    return {
        confirmDelete(item) {
            window.showModal(
                'Confirmar Exclusão',
                `Tem certeza que deseja excluir "${item.name || item.public_id}"? Esta ação não pode ser desfeita.`,
                async () => {
                    const { success } = await window.api(urlBuilder(item), { method: 'DELETE' });
                    window.closeModal();
                    if (success) {
                        window.showAlert(successMessage, 'success');
                        if (typeof this.loadData === 'function') await this.loadData();
                    }
                }
            );
        },
    };
}
