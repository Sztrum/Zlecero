type ProcessStep = {
    label: string;
    status: string;
    icon: string;
};

const processSteps: ProcessStep[] = [
    {label: "E-mail", status: "Nowa wiadomość", icon: "mail"},
    {label: "Zapytanie", status: "Nowe", icon: "inbox"},
    {label: "Oferta", status: "Szkic oferty", icon: "file"},
    {label: "Akceptacja", status: "Zaakceptowana", icon: "check"},
    {label: "Zlecenie", status: "Zlecenie utworzone", icon: "doc"},
    {label: "Realizacja", status: "W realizacji", icon: "flow"},
];

const iconMap: Record<string, string> = {
    mail: "✉",
    inbox: "▣",
    file: "▤",
    check: "✓",
    doc: "▥",
    flow: "↝",
};

const badgeClasses = [
    "zl-badge--blue",
    "zl-badge--primary",
    "zl-badge--orange",
    "zl-badge--green",
    "zl-badge--purple",
    "zl-badge--cream",
];

function setProcessCard(card: HTMLElement | null, index: number): void {
    if (! card) {
        return;
    }

    const step = processSteps[Math.max(0, Math.min(processSteps.length - 1, index))];
    const previousIndex = Number(card.dataset.activeStep ?? "0");
    const stage = card.querySelector<HTMLElement>("[data-zl-stage]");
    const status = card.querySelector<HTMLElement>("[data-zl-status]");
    const icon = card.querySelector<HTMLElement>("[data-zl-icon]");
    const progress = card.querySelector<HTMLElement>("[data-zl-progress]");
    const rail = card.querySelector<HTMLElement>(".zl-process-card__rail");
    const content = card.querySelector<HTMLElement>(".zl-process-card__content");

    card.dataset.activeStep = String(index);

    if (stage) {
        stage.textContent = step.label;
    }

    if (status) {
        status.textContent = step.status;
        status.classList.remove(...badgeClasses);
        status.classList.add(badgeClasses[index] ?? badgeClasses[0]);
    }

    if (icon) {
        icon.textContent = iconMap[step.icon] ?? iconMap.mail;
    }

    if (progress) {
        progress.textContent = index === 0
            ? "Analiza wiadomości"
            : index < 3
                ? "Dane są przygotowane do kolejnego kroku"
                : "Historia sprawy została zaktualizowana";
    }

    if (rail) {
        rail.style.transform = `translateY(${index * 48}px)`;
    }

    if (content && previousIndex !== index && ! window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
        content.classList.remove("is-entering");
        void content.offsetWidth;
        content.classList.add("is-entering");
    }
}

function initHeroDemo(page: HTMLElement): void {
    const demo = page.querySelector<HTMLElement>(".zl-demo");

    if (! demo) {
        return;
    }

    let phase = 0;
    let visible = true;
    let timer: number | null = null;
    const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    const status = demo.querySelector<HTMLElement>("[data-zl-hero-status]");
    const pill = demo.querySelector<HTMLElement>("[data-zl-hero-pill]");
    const inquiryBadge = demo.querySelector<HTMLElement>("[data-zl-hero-badge]");

    const applyPhase = (): void => {
        demo.dataset.heroPhase = String(phase);
        const selected = phase >= 1;
        const readyToQuote = phase >= 3;

        if (status) {
            status.textContent = readyToQuote
                ? "Zapytanie gotowe do przygotowania oferty"
                : selected
                    ? "AI rozpoznaje dane z wiadomości"
                    : "Czekam na nowe zapytanie";
        }

        if (pill) {
            pill.textContent = readyToQuote ? "Gotowe do wyceny" : "Analiza zapytania";
        }

        if (inquiryBadge) {
            inquiryBadge.textContent = readyToQuote ? "Gotowe do wyceny" : "Nowe";
            inquiryBadge.classList.toggle("zl-badge--green", readyToQuote);
            inquiryBadge.classList.toggle("zl-badge--blue", ! readyToQuote);
        }
    };

    const stop = (): void => {
        if (timer !== null) {
            window.clearInterval(timer);
            timer = null;
        }
    };

    const start = (): void => {
        stop();

        if (! visible || reducedMotion) {
            return;
        }

        timer = window.setInterval(() => {
            phase = (phase + 1) % 4;
            applyPhase();
        }, 2200);
    };

    if (reducedMotion) {
        phase = 3;
        applyPhase();
        return;
    }

    const observer = new IntersectionObserver(([entry]) => {
        visible = entry.isIntersecting;
        start();
    }, {threshold: 0.2});

    observer.observe(demo);
    applyPhase();
    start();
}

function initProcessStory(page: HTMLElement): void {
    const story = page.querySelector<HTMLElement>(".zl-process-story");
    const items = Array.from(page.querySelectorAll<HTMLElement>(".zl-timeline__item"));
    const card = page.querySelector<HTMLElement>(".zl-process-card--story");
    const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    const desktop = window.matchMedia("(min-width: 1024px)");
    let active = 0;

    const applyActive = (index: number): void => {
        active = Math.max(0, Math.min(items.length - 1, index));
        items.forEach((item, itemIndex) => item.classList.toggle("is-active", itemIndex === active));
        setProcessCard(card, active);
    };

    const onScroll = (): void => {
        if (! story || ! desktop.matches || reducedMotion) {
            return;
        }

        const rect = story.getBoundingClientRect();
        const progress = Math.max(0, Math.min(0.999, -rect.top / Math.max(rect.height - window.innerHeight, 1)));

        applyActive(Math.floor(progress * items.length));
    };

    applyActive(0);
    onScroll();
    window.addEventListener("scroll", onScroll, {passive: true});
    window.addEventListener("resize", onScroll);
}

function initBenefits(page: HTMLElement): void {
    const benefits = Array.from(page.querySelectorAll<HTMLElement>(".zl-benefit"));
    const card = page.querySelector<HTMLElement>(".zl-process-card--benefits");

    const applyActive = (index: number): void => {
        benefits.forEach((benefit, benefitIndex) => benefit.classList.toggle("is-active", benefitIndex === index));
        setProcessCard(card, Math.min(index + 1, processSteps.length - 1));
    };

    benefits.forEach((benefit, index) => {
        benefit.addEventListener("mouseenter", () => applyActive(index));
        benefit.addEventListener("focus", () => applyActive(index));
        benefit.addEventListener("click", () => applyActive(index));
    });

    applyActive(0);
}

function initFaq(page: HTMLElement): void {
    const items = Array.from(page.querySelectorAll<HTMLElement>(".zl-faq-item, .zl-faq-card"));

    items.forEach((item) => {
        const button = item.querySelector<HTMLButtonElement>(".zl-faq-item__button, .zl-faq-card__button");

        if (! button) {
            return;
        }

        button.addEventListener("click", () => {
            const isOpen = item.classList.contains("is-open");
            items.forEach((candidate) => {
                candidate.classList.remove("is-open");
                candidate.querySelector<HTMLButtonElement>(".zl-faq-item__button, .zl-faq-card__button")?.setAttribute("aria-expanded", "false");
            });

            if (! isOpen) {
                item.classList.add("is-open");
                button.setAttribute("aria-expanded", "true");
            }
        });
    });
}

function initCta(page: HTMLElement): void {
    const cta = page.querySelector<HTMLElement>(".zl-cta");

    if (! cta) {
        return;
    }

    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
        cta.classList.add("is-revealed");
        return;
    }

    const observer = new IntersectionObserver(([entry]) => {
        if (entry.isIntersecting) {
            cta.classList.add("is-revealed");
            observer.disconnect();
        }
    }, {threshold: 0.35});

    observer.observe(cta);
}

function initMobileMenu(page: HTMLElement): void {
    const menu = page.querySelector<HTMLDetailsElement>(".zl-mobile-menu");

    if (! menu) {
        return;
    }

    menu.querySelectorAll("a").forEach((link) => {
        link.addEventListener("click", () => {
            menu.open = false;
        });
    });
}

document.addEventListener("DOMContentLoaded", () => {
    const page = document.querySelector<HTMLElement>(".zl-page");

    if (! page) {
        return;
    }

    page.classList.add("is-js");
    initHeroDemo(page);
    initProcessStory(page);
    initBenefits(page);
    initFaq(page);
    initCta(page);
    initMobileMenu(page);
});
