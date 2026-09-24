(function () {
        const onboarding = [
          [
            "Sarah Lee",
            "user2.png",
            "Frontend Developer",
            "Apr 20, 2025",
            [
              "Offer signed",
              "Equipment issued",
              "Accounts created",
              "Orientation",
              "Team intro",
            ],
            3,
          ],
          [
            "Tom Ford",
            "user6.png",
            "Product Designer",
            "Apr 22, 2025",
            [
              "Offer signed",
              "Equipment issued",
              "Accounts created",
              "Orientation",
              "Team intro",
            ],
            5,
          ],
          [
            "Ria Sen",
            "user8.png",
            "SEO Specialist",
            "Apr 25, 2025",
            [
              "Offer signed",
              "Equipment issued",
              "Accounts created",
              "Orientation",
              "Team intro",
            ],
            2,
          ],
          [
            "Leo Carter",
            "user1.png",
            "Backend Intern",
            "Apr 28, 2025",
            [
              "Offer signed",
              "Equipment issued",
              "Accounts created",
              "Orientation",
              "Team intro",
            ],
            1,
          ],
          [
            "Ava Stone",
            "user5.png",
            "Product Designer",
            "May 02, 2025",
            [
              "Offer signed",
              "Equipment issued",
              "Accounts created",
              "Orientation",
              "Team intro",
            ],
            4,
          ],
          [
            "Ken Adams",
            "user7.png",
            "Sales Executive",
            "May 05, 2025",
            [
              "Offer signed",
              "Equipment issued",
              "Accounts created",
              "Orientation",
              "Team intro",
            ],
            0,
          ],
        ];
        const offboarding = [
          [
            "Carla Diaz",
            "user9.png",
            "Marketing Lead",
            "Apr 30, 2025",
            [
              "Knowledge transfer",
              "Asset return",
              "Access revoked",
              "Exit interview",
              "Final settlement",
            ],
            2,
          ],
          [
            "Sam Reed",
            "user4.png",
            "QA Engineer",
            "May 10, 2025",
            [
              "Knowledge transfer",
              "Asset return",
              "Access revoked",
              "Exit interview",
              "Final settlement",
            ],
            4,
          ],
        ];

        const card = (c) => {
          const [name, img, role, date, items, done] = c;
          const pct = Math.round((done / items.length) * 100);
          return `
            <article class="bg-surface border border-border rounded-2xl p-4 flex flex-col">
              <div class="flex items-center gap-3">
                <img src="./assets/images/${img}" alt="" class="w-11 h-11 rounded-full object-cover flex-shrink-0" />
                <div class="min-w-0 flex-1">
                  <p class="text-sm font-semibold text-heading truncate">${name}</p>
                  <p class="text-xs text-muted truncate">${role}</p>
                </div>
                <span class="text-[11px] text-muted whitespace-nowrap flex items-center gap-1"><i class="ph ph-calendar-blank"></i>${date}</span>
              </div>
              <div class="mt-3">
                <div class="flex items-center justify-between text-xs mb-1.5">
                  <span class="text-muted">Progress</span>
                  <span class="text-heading font-semibold">${done}/${items.length} · ${pct}%</span>
                </div>
                <div class="h-1.5 rounded-full bg-subtle overflow-hidden">
                  <div class="h-full rounded-full ${pct === 100 ? "bg-success" : "bg-primary"}" style="width: ${pct}%"></div>
                </div>
              </div>
              <ul class="mt-3 pt-3 border-t border-border-subtle space-y-2">
                ${items
                  .map(
                    (label, idx) => `
                  <li class="flex items-center gap-2 text-sm">
                    <i class="ph ${idx < done ? "ph-check-circle text-success" : "ph-circle text-faint"} text-base"></i>
                    <span class="${idx < done ? "text-muted line-through" : "text-text"}">${label}</span>
                  </li>`,
                  )
                  .join("")}
              </ul>
            </article>`;
        };

        const ob = document.querySelector('[data-cards="onboarding"]');
        if (ob) ob.innerHTML = onboarding.map(card).join("");
        const off = document.querySelector('[data-cards="offboarding"]');
        if (off) off.innerHTML = offboarding.map(card).join("");
      })();
