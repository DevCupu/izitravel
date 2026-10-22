export default () => ({
    dragged: null,
    hoverStage: null,
    activeStage: '',
    viewMode: 'card',
    stages: [],
    selectedIds: [],
    bulkAction: 'move',
    bulkTarget: '',
    bulkCsId: '',
    selected: null,
    details: null,
    detailsName: '',
    detailsPackageId: '',
    detailsCity: '',
    detailsDeparture: '',
    detailsAssigneeId: '',
    targetStage: '',
    quickUrl: '',
    quickStage: '',
    confirmationNote: '',
    confirmedOn: '',
    bookingReference: '',
    lostReason: '',

    init() {
        this.stages = [...this.$refs.board.querySelectorAll('[data-column-stage]')].map((section) => section.dataset.columnStage);
        this.activeStage = this.stages[0] || '';
        try {
            if (localStorage.getItem('crm-pipeline-view') === 'table' && !window.matchMedia('(max-width: 639px)').matches) this.viewMode = 'table';
        } catch (_) { /* Storage may be unavailable in private browsing. */ }
        window.matchMedia('(max-width: 639px)').addEventListener('change', (event) => {
            if (event.matches) this.viewMode = 'card';
            else {
                try { this.viewMode = localStorage.getItem('crm-pipeline-view') === 'table' ? 'table' : 'card'; }
                catch (_) { this.viewMode = 'card'; }
            }
        });
        this.$nextTick(() => {
            try {
                const destination = sessionStorage.getItem('crm-pipeline-destination');
                if (destination) {
                    this.scrollToStage(destination);
                    sessionStorage.removeItem('crm-pipeline-destination');
                }
                sessionStorage.removeItem('crm-pipeline-scroll');
            } catch (_) { /* Storage may be unavailable in private browsing. */ }

            const rootData = this.$refs.board.parentElement.dataset;
            const oldId = rootData.oldLeadId;
            if (!oldId) return;
            const card = [...this.$refs.board.querySelectorAll('[data-lead-id]')]
                .find((item) => item.dataset.leadId === oldId);
            if (card) {
                this.activeStage = card.dataset.stage;
                if (rootData.oldPipelineForm === 'details') {
                    this.openDetails(this.cardData(card), true);
                } else {
                    this.openMove(this.cardData(card), rootData.oldStage || '');
                }
            }
        });
    },

    cardData(card) {
        return {
            id: card.dataset.leadId,
            name: card.dataset.leadName,
            stage: card.dataset.stage,
            url: card.dataset.stageUrl,
            detailsUrl: card.dataset.detailsUrl,
            showUrl: card.dataset.showUrl,
            packageId: card.dataset.packageId || '',
            city: card.dataset.city || '',
            departure: card.dataset.plannedDepartureMonth || '',
            assignedCsId: card.dataset.assignedCsId || '',
            confirmationNote: card.dataset.confirmationNote || '',
            confirmedOn: card.dataset.confirmedOn || '',
            bookingReference: card.dataset.bookingReference || '',
            lostReason: card.dataset.lostReason || '',
        };
    },

    beginDrag(event) {
        const card = event.currentTarget.closest('[data-lead-id]');
        this.dragged = this.cardData(card);
        event.dataTransfer.effectAllowed = 'move';
        event.dataTransfer.setData('text/plain', this.dragged.id);
        event.dataTransfer.setDragImage(card, 28, 20);
    },

    endDrag() {
        this.dragged = null;
        this.hoverStage = null;
    },

    allowDrop(event, stage) {
        if (!this.dragged || this.dragged.stage === stage) return;
        event.preventDefault();
        event.dataTransfer.dropEffect = 'move';
        this.hoverStage = stage;
    },

    leaveDrop(event) {
        if (!event.currentTarget.contains(event.relatedTarget)) this.hoverStage = null;
    },

    drop(event, stage) {
        event.preventDefault();
        const lead = this.dragged;
        this.endDrag();
        if (!lead || lead.stage === stage) return;
        if (stage === 'berhasil' || stage === 'gagal') {
            this.openMove(lead, stage);
            return;
        }
        this.quickUrl = lead.url;
        this.quickStage = stage;
        this.savePosition(stage);
        this.$nextTick(() => this.$refs.quickStageForm.requestSubmit());
    },

    openMoveFromButton(event) {
        this.openMove(this.cardData(event.currentTarget.closest('[data-lead-id]')));
    },

    openDetailsFromButton(event) {
        this.openDetails(this.cardData(event.currentTarget.closest('[data-lead-id]')));
    },

    openDetails(lead, restoring = false) {
        const old = this.$refs.board.parentElement.dataset;
        this.details = { ...lead, url: lead.detailsUrl };
        this.detailsName = restoring ? (old.oldDisplayName ?? lead.name) : lead.name;
        this.detailsPackageId = restoring ? (old.oldPackageId ?? lead.packageId) : lead.packageId;
        this.detailsCity = restoring ? (old.oldCity ?? lead.city) : lead.city;
        this.detailsDeparture = restoring ? (old.oldPlannedDepartureMonth ?? lead.departure) : lead.departure;
        this.detailsAssigneeId = restoring ? (old.oldAssignedCsId ?? lead.assignedCsId) : lead.assignedCsId;
        this.$nextTick(() => this.$refs.detailsDialog.showModal());
    },

    openMove(lead, stage = '') {
        const rootData = this.$refs.board.parentElement.dataset;
        const restoring = rootData.oldLeadId === lead.id;
        this.selected = lead;
        this.targetStage = stage;
        this.confirmationNote = restoring ? (rootData.oldConfirmationNote || '') : lead.confirmationNote;
        this.confirmedOn = restoring ? (rootData.oldConfirmedOn || '') : (lead.confirmedOn || rootData.today);
        this.bookingReference = restoring ? (rootData.oldBookingReference || '') : lead.bookingReference;
        this.lostReason = restoring ? (rootData.oldLostReason || '') : lead.lostReason;
        this.$nextTick(() => this.$refs.stageDialog.showModal());
    },

    savePosition(stage = this.targetStage) {
        try {
            if (stage) sessionStorage.setItem('crm-pipeline-destination', stage);
        }
        catch (_) { /* Form submission still works without storage. */ }
    },

    visibleIds() {
        if (!this.$refs.board) return [];
        const column = [...this.$refs.board.querySelectorAll('[data-column-stage]')]
            .find((section) => section.dataset.columnStage === this.activeStage);
        return column ? [...column.querySelectorAll('article[data-lead-id]')]
            .map((card) => card.dataset.leadId) : [];
    },

    toggleAllVisible() {
        const ids = this.visibleIds();
        this.selectedIds = ids.every((id) => this.selectedIds.includes(id))
            ? this.selectedIds.filter((id) => !ids.includes(id))
            : [...new Set([...this.selectedIds, ...ids])];
    },

    resetBulk() {
        this.selectedIds = [];
        this.bulkAction = 'move';
        this.bulkTarget = '';
        this.bulkCsId = '';
    },

    submitBulk(event) {
        if (!this.selectedIds.length ||
            (this.bulkAction === 'move' && (!this.bulkTarget || this.bulkTarget === this.activeStage)) ||
            (this.bulkAction === 'assign' && !this.bulkCsId)) {
            event.preventDefault();
            return;
        }
        if (this.bulkAction === 'archive' && !window.confirm(`Arsipkan ${this.selectedIds.length} lead terpilih?`)) {
            event.preventDefault();
            return;
        }
        this.savePosition(this.activeStage);
    },

    scrollToStage(stage) {
        if (this.activeStage !== stage) this.resetBulk();
        this.activeStage = stage;
    },

    setViewMode(mode) {
        if (mode !== 'card' && mode !== 'table') return;
        if (mode === 'table' && window.matchMedia('(max-width: 639px)').matches) return;
        this.viewMode = mode;
        try {
            localStorage.setItem('crm-pipeline-view', mode);
        } catch (_) { /* The view still changes when storage is unavailable. */ }
    },

    stepStage(direction) {
        const index = this.stages.indexOf(this.activeStage);
        this.scrollToStage(this.stages[Math.max(0, Math.min(this.stages.length - 1, index + direction))]);
    },
});
