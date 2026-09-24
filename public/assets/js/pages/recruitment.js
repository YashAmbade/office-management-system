(function () {
        const statusPill = (s) => {
          const map = {
            Open: '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-success-soft text-success text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-success"></span>Open</span>',
            Closed:
              '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#f59e0b]/12 text-[#b45309] text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-[#f59e0b]"></span>Closed</span>',
            Draft:
              '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-subtle text-text-secondary text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-faint"></span>Draft</span>',
          };
          return map[s] || s;
        };

        // [title, dept, type, applicants, posted, status, location, description]
        const jobs = [
          ["Senior Frontend Developer", "Engineering", "Full Time", 48, "Apr 02, 2025", "Open", "Remote · San Francisco", "Build and ship the customer-facing dashboard using modern frontend tooling and a shared design system."],
          ["Product Designer", "Design", "Full Time", 32, "Apr 05, 2025", "Open", "Hybrid · New York", "Own end-to-end product flows from research and wireframes through polished, accessible UI."],
          ["SEO Specialist", "Marketing", "Contract", 21, "Apr 08, 2025", "Open", "Remote", "Plan and execute on-page and technical SEO to grow organic traffic and improve rankings."],
          ["HR Coordinator", "Human Resources", "Full Time", 17, "Mar 28, 2025", "Closed", "On-site · Austin", "Support recruitment, onboarding and day-to-day people operations across the company."],
          ["Sales Executive", "Sales", "Full Time", 39, "Apr 11, 2025", "Open", "Hybrid · Chicago", "Drive new business, manage the pipeline and close deals with mid-market clients."],
          ["Backend Intern", "Engineering", "Internship", 14, "Apr 14, 2025", "Draft", "Remote", "Assist the platform team with APIs, testing and tooling under senior engineer mentorship."],
        ];

        const esc = (s) =>
          String(s).replace(/"/g, "&quot;").replace(/</g, "&lt;");

        const actions = (r) => `
          <div class="flex items-center justify-end gap-1">
            <button
              type="button"
              data-modal-open="job-view-modal"
              data-job-view
              data-title="${esc(r[0])}"
              data-dept="${esc(r[1])}"
              data-type="${esc(r[2])}"
              data-applicants="${esc(r[3])}"
              data-posted="${esc(r[4])}"
              data-status="${esc(r[5])}"
              data-location="${esc(r[6])}"
              data-desc="${esc(r[7])}"
              class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle hover:text-primary transition-colors"
              aria-label="View"><i class="ph ph-eye"></i></button>
            <button class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle hover:text-primary transition-colors" aria-label="Edit"><i class="ph ph-pencil-simple"></i></button>
            <button class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle hover:text-danger transition-colors" aria-label="Delete"><i class="ph ph-trash"></i></button>
          </div>`;
        const jobsBody = document.querySelector('[data-rows="jobs"]');
        if (jobsBody) {
          jobsBody.innerHTML = jobs
            .map(
              (r, i) => `
              <tr class="${i < jobs.length - 1 ? "border-b border-border-subtle " : ""}hover:bg-subtle/40 transition-colors">
                <td class="py-3 px-4 font-medium text-heading whitespace-nowrap">${r[0]}</td>
                <td class="py-3 px-2 text-text-secondary whitespace-nowrap">${r[1]}</td>
                <td class="py-3 px-2 text-text-secondary whitespace-nowrap">${r[2]}</td>
                <td class="py-3 px-2 text-text-secondary">${r[3]}</td>
                <td class="py-3 px-2 text-text-secondary whitespace-nowrap">${r[4]}</td>
                <td class="py-3 px-2">${statusPill(r[5])}</td>
                <td class="py-3 px-2 pr-4">${actions(r)}</td>
              </tr>`,
            )
            .join("");
        }

        // Populate the Job Details modal from the clicked View button
        const modal = document.getElementById("job-view-modal");
        document.addEventListener("click", (e) => {
          const btn = e.target.closest("[data-job-view]");
          if (!btn || !modal) return;
          const set = (field, value) => {
            const el = modal.querySelector(`[data-job-field="${field}"]`);
            if (el) el.textContent = value;
          };
          set("title", btn.dataset.title);
          set("location", btn.dataset.location);
          set("dept", btn.dataset.dept);
          set("type", btn.dataset.type);
          set("applicants", btn.dataset.applicants + " applicants");
          set("posted", btn.dataset.posted);
          set("desc", btn.dataset.desc);
          const statusEl = modal.querySelector('[data-job-field="status"]');
          if (statusEl) statusEl.innerHTML = statusPill(btn.dataset.status);
        });

        // Resume file picker (Add Candidate modal)
        const resumeInput = document.getElementById("resume-input");
        const resumeBtn = document.querySelector("[data-resume-pick]");
        const resumeLabel = document.querySelector("[data-resume-label]");
        if (resumeInput && resumeBtn) {
          resumeBtn.addEventListener("click", () => resumeInput.click());
          resumeInput.addEventListener("change", () => {
            const file = resumeInput.files && resumeInput.files[0];
            if (!file) return;
            if (file.type !== "application/pdf") {
              alert("Please choose a PDF file.");
              resumeInput.value = "";
              return;
            }
            if (resumeLabel) resumeLabel.textContent = file.name;
            resumeBtn.classList.remove("text-muted", "border-dashed");
            resumeBtn.classList.add("text-text");
          });
        }

        // Candidate pipeline kanban
        const columns = [
          [
            "Applied",
            "bg-[#3b82f6]",
            [
              ["Sarah Lee", "user2.png", "Frontend Developer"],
              ["Tom Ford", "user6.png", "Product Designer"],
              ["Ria Sen", "user8.png", "SEO Specialist"],
            ],
          ],
          [
            "Screening",
            "bg-[#8b5cf6]",
            [
              ["Mark Diaz", "user4.png", "Frontend Developer"],
              ["Nina Roy", "user9.png", "Sales Executive"],
            ],
          ],
          [
            "Interview",
            "bg-[#f59e0b]",
            [
              ["Leo Carter", "user1.png", "Backend Intern"],
              ["Ava Stone", "user5.png", "Product Designer"],
            ],
          ],
          [
            "Offer",
            "bg-[#16a34a]",
            [["Ken Adams", "user7.png", "Frontend Developer"]],
          ],
          [
            "Hired",
            "bg-success",
            [["Maya Khan", "user3.png", "SEO Specialist"]],
          ],
        ];
        const pipe = document.querySelector("[data-pipeline]");
        if (pipe) {
          pipe.innerHTML = columns
            .map(
              ([title, dot, cards]) => `
              <div class="w-[260px] flex-shrink-0 flex flex-col bg-subtle/50 rounded-2xl p-3">
                <div class="flex items-center justify-between px-1 mb-3">
                  <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full ${dot}"></span>
                    <h3 class="text-sm font-semibold text-heading">${title}</h3>
                    <span class="text-[11px] font-medium text-muted bg-surface border border-border rounded-full px-2 py-0.5">${String(cards.length).padStart(2, "0")}</span>
                  </div>
                </div>
                <div class="flex-1 space-y-3">
                  ${cards
                    .map(
                      (c) => `
                    <article class="bg-surface border border-border rounded-xl p-3 cursor-pointer hover:shadow-md transition-shadow">
                      <div class="flex items-center gap-2.5">
                        <img src="./assets/images/${c[1]}" alt="" class="w-9 h-9 rounded-full object-cover" />
                        <div class="min-w-0">
                          <p class="text-sm font-semibold text-heading truncate">${c[0]}</p>
                          <p class="text-[11px] text-muted truncate">${c[2]}</p>
                        </div>
                      </div>
                    </article>`,
                    )
                    .join("")}
                </div>
              </div>`,
            )
            .join("");
        }
      })();
