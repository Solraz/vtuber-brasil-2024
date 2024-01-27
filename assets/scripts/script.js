const load_page = async () => {
	let url = window.location.href;
	const page = window.location.pathname;
	const origin = window.location.origin;
	const main = document.querySelector(`main`);

	if (page === "/vtuber-brasil-2024/") {
		url = `https://solraz.github.io/vtuber-brasil-2024/pages/initial`;
	} else {
		url = `https://solraz.github.io/vtuber-brasil-2024/pages${page}`;
	}

	htmx.ajax("GET", url, { target: "main>container", swap: "innerHTML" });
};

const get_cookie = (name) => {
	let matches = document.cookie.match(
		new RegExp(
			"(?:^|; )" +
				name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, "\\$1") +
				"=([^;]*)"
		)
	);
	return matches ? decodeURIComponent(matches[1]) : undefined;
};
const set_cookie = (name, value, options = {}) => {
	options = {
		path: "/",
		// add other defaults here if necessary
		...options,
	};

	if (options.expires instanceof Date) {
		options.expires = options.expires.toUTCString();
	}

	let updatedCookie =
		encodeURIComponent(name) + "=" + encodeURIComponent(value);

	for (let optionKey in options) {
		updatedCookie += "; " + optionKey;
		let optionValue = options[optionKey];
		if (optionValue !== true) {
			updatedCookie += "=" + optionValue;
		}
	}

	document.cookie = updatedCookie;
};
const delete_cookie = (name) => {
	set_cookie(name, "", {
		"max-age": -1,
	});
};
const cookies = async () => {
	let cookie = get_cookie("voter_id");

	if (cookie) {
		window.voter = cookie;
		return;
	}

	const origin = window.location.origin;
	let new_cookie = await fetch(
		`https://solraz.github.io/vtuber-brasil-2024/api/cookies.json`,
		{
			method: "GET",
		}
	);
	new_cookie = await new_cookie.json();

	let age = new Date();
	age.setTime(age.getTime() + 1 * 24 * 60 * 60 * 1000);
	set_cookie("voter_id", new_cookie["uuid"], {
		"max-age": age,
	});
};

const fill_winners = async () => {
	let params_year = new URLSearchParams(document.location.search);
	let year = params_year.get("year");

	if (!year) year = 2020;

	let winners = document.querySelector(`winners`);

	let winner_url =
		year !== "" ? `/api/years/${year}.json` : `/api/years/2020.json`;
	let fill_winners = await fetch(
		`https://solraz.github.io/vtuber-brasil-2024${winner_url}`,
		{
			method: "GET",
		}
	);
	fill_winners = await fill_winners.json();

	winners.innerHTML = "";

	for (const [key, value] of Object.entries(fill_winners)) {
		winners.innerHTML += `
			<winner>
				<div class="category">${key}</div>
				<div class="public">
					<img src="./assets/img/logo.webp" alt="">
					<div>
						<h6>Público</h6>
						<h4>${value.public}</h4>
					</div>

					<open-new>
						<img src="./assets/img/open_in_new.svg" alt="">
					</open-new>
				</div>
				<div class="jury">
					<img src="./assets/img/logo.webp" alt="">
					<div>
						<h6>Jurados</h6>
						<h4>${value.jury}</h4>
					</div>

					<open-new>
						<img src="./assets/img/open_in_new.svg" alt="">
					</open-new>
				</div>
			</winner>
		`;
	}
};
const winner_navigation = () => {
	let params_year = new URLSearchParams(document.location.search);
	let year = params_year.get("year");

	if (!year) year = 2020;

	let container_years = document.querySelectorAll(`years`);
	let current_selected = document.querySelectorAll(`[data-year="${year}"]`);
	let other_years = document.querySelectorAll(`years [data-year]`);

	let current_selected_pos = current_selected[0].getBoundingClientRect();
	let container_pos = container_years[0].getBoundingClientRect();

	for (const c of container_years) {
		c.style.left = `calc(-${current_selected_pos.x}px + ${container_pos.x}px)`;
	}

	for (const e of other_years) {
		e.classList.remove(`active`);
	}

	for (const e of current_selected) {
		e.classList.add(`active`);
	}

	for (const e of other_years) {
		e.addEventListener("click", () => {
			year = e.dataset.year;
			current_selected = document.querySelectorAll(`[data-year="${year}"]`);

			current_selected_pos = current_selected[0].getBoundingClientRect();
			container_pos = container_years[0].getBoundingClientRect();

			for (const c of container_years) {
				c.style.left = `calc(-${current_selected_pos.x}px + ${container_pos.x}px)`;
			}

			e.classList.add(`active`);

			for (const o of other_years) {
				if (o === e) continue;
				e.classList.remove(`active`);
			}
		});
	}

	window.addEventListener("resize", () => {
		current_selected_pos = current_selected[0].getBoundingClientRect();
		container_pos = container_years[0].getBoundingClientRect();

		for (const c of container_years) {
			c.style.left = `calc(-${current_selected_pos.x}px + ${container_pos.x}px)`;
		}
	});
};

const fill_category = () => {
	const params_category = new URLSearchParams(document.location.search);
	let category = params_category.get("category");

	if (!category) category = "mascote";

	document.querySelector(`voting-booth .title h2`).textContent =
		category.toUpperCase();

	let current_category = document.querySelector(
		`category[hx-get$="=${category}"]`
	);
	current_category.classList.add(`selected`);

	htmx.on("htmx:xhr:loadend", () => {
		let items = document.querySelectorAll(`entries > item`);

		if (items.length == 1) {
			document.querySelector(`.golden`).textContent = "Voltar";
		}
	});

	return category;
};

window.addEventListener("load", () => {
	load_page();
	cookies();
});
