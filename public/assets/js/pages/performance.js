(function () {
        const ratingStars = (n) => {
          let out =
            '<span class="inline-flex items-center gap-0.5 text-[#f59e0b]">';
          for (let i = 1; i <= 5; i++)
            out += `<i class="ph ${i <= n ? "ph-star-fill" : "ph-star"} text-sm"></i>`;
          out += `</span>`;
          return out;
        };
        const statusPill = (s) => {
          const map = {
            Completed:
              '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-success-soft text-success text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-success"></span>Completed</span>',
            "In Progress":
              '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-primary-soft text-primary text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-primary"></span>In Progress</span>',
            Pending:
              '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#f59e0b]/12 text-[#b45309] text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-[#f59e0b]"></span>Pending</span>',
            Closed:
              '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-subtle text-text-secondary text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-faint"></span>Closed</span>',
          };
          return map[s] || s;
        };

        const reviews = [
          ["Tahsan Khan", "user1.png", "Q2 2025", "Jenson Roy", 5, "Completed"],
          [
            "Anwar Hussain",
            "user4.png",
            "Q2 2025",
            "Jenson Roy",
            4,
            "Completed",
          ],
          [
            "Jelin Jack",
            "user5.png",
            "Q2 2025",
            "Salina Roy",
            4,
            "In Progress",
          ],
          ["Mim Khan", "user9.png", "Q2 2025", "Salina Roy", 3, "Pending"],
          ["Herry Kane", "user6.png", "Q2 2025", "Jenson Roy", 5, "Completed"],
        ];
        const rBody = document.querySelector('[data-rows="reviews"]');
        if (rBody) {
          rBody.innerHTML = reviews
            .map(
              (r, i) => `
              <tr class="${i < reviews.length - 1 ? "border-b border-border-subtle " : ""}hover:bg-subtle/40 transition-colors">
                <td class="py-3 px-4">
                  <span class="inline-flex items-center gap-2 whitespace-nowrap">
                    <img src="./assets/images/${r[1]}" alt="" class="w-8 h-8 rounded-full object-cover" />
                    <span class="font-medium text-heading">${r[0]}</span>
                  </span>
                </td>
                <td class="py-3 px-2 text-text-secondary whitespace-nowrap">${r[2]}</td>
                <td class="py-3 px-2 text-text-secondary whitespace-nowrap">${r[3]}</td>
                <td class="py-3 px-2">${ratingStars(r[4])}</td>
                <td class="py-3 px-2">${statusPill(r[5])}</td>
                <td class="py-3 px-2 pr-4 text-right">
                  <button class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-muted hover:bg-subtle hover:text-primary transition-colors" aria-label="View"><i class="ph ph-eye"></i></button>
                </td>
              </tr>`,
            )
            .join("");
        }

        const goals = [
          [
            "Launch v2 of the mobile app",
            "Engineering",
            "Anwar Hussain",
            "user4.png",
            72,
            "On Track",
          ],
          [
            "Grow organic traffic by 40%",
            "Marketing",
            "Mim Khan",
            "user9.png",
            55,
            "On Track",
          ],
          [
            "Reduce churn to under 3%",
            "Company",
            "Jenson Roy",
            "user7.png",
            38,
            "At Risk",
          ],
          [
            "Ship design system 1.0",
            "Design",
            "Jelin Jack",
            "user5.png",
            90,
            "On Track",
          ],
        ];
        const gBox = document.querySelector('[data-cards="goals"]');
        if (gBox) {
          gBox.innerHTML = goals
            .map((g) => {
              const [title, cat, owner, img, pct, health] = g;
              const healthPill =
                health === "On Track"
                  ? '<span class="inline-flex items-center px-2 py-0.5 rounded-full bg-success-soft text-success text-[11px] font-medium">On Track</span>'
                  : '<span class="inline-flex items-center px-2 py-0.5 rounded-full bg-danger-soft text-danger text-[11px] font-medium">At Risk</span>';
              return `
              <article class="rounded-2xl border border-border p-4">
                <div class="flex flex-wrap items-start justify-between gap-2">
                  <p class="text-sm font-semibold text-heading">${title}</p>
                  ${healthPill}
                </div>
                <div class="flex items-center gap-2 mt-2 text-xs text-muted">
                  <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-subtle text-text-secondary font-medium">${cat}</span>
                  <span class="inline-flex items-center gap-1.5"><img src="./assets/images/${img}" alt="" class="w-5 h-5 rounded-full object-cover" />${owner}</span>
                </div>
                <div class="mt-3">
                  <div class="flex items-center justify-between text-xs mb-1.5"><span class="text-muted">Progress</span><span class="text-heading font-semibold">${pct}%</span></div>
                  <div class="h-1.5 rounded-full bg-subtle overflow-hidden"><div class="h-full rounded-full ${health === "At Risk" ? "bg-danger" : "bg-primary"}" style="width: ${pct}%"></div></div>
                </div>
              </article>`;
            })
            .join("");
        }

        const cycles = [
          [
            "Q2 2025 Review",
            "Apr 01 – Jun 30, 2025",
            "186 / 228",
            "In Progress",
          ],
          ["Q1 2025 Review", "Jan 01 – Mar 31, 2025", "228 / 228", "Completed"],
          ["Annual 2024", "Jan 01 – Dec 31, 2024", "210 / 210", "Closed"],
          ["Q3 2025 Review", "Jul 01 – Sep 30, 2025", "0 / 230", "Pending"],
        ];
        const cBody = document.querySelector('[data-rows="cycles"]');
        if (cBody) {
          cBody.innerHTML = cycles
            .map(
              (c, i) => `
              <tr class="${i < cycles.length - 1 ? "border-b border-border-subtle " : ""}hover:bg-subtle/40 transition-colors">
                <td class="py-3 px-4 font-medium text-heading whitespace-nowrap">${c[0]}</td>
                <td class="py-3 px-2 text-text-secondary whitespace-nowrap">${c[1]}</td>
                <td class="py-3 px-2 text-text-secondary">${c[2]}</td>
                <td class="py-3 px-2">${statusPill(c[3])}</td>
                <td class="py-3 px-2 pr-4 text-right">
                  <button class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-muted hover:bg-subtle hover:text-primary transition-colors" aria-label="View"><i class="ph ph-arrow-right"></i></button>
                </td>
              </tr>`,
            )
            .join("");
        }
      })();
