(function () {
        const statusPill = (s) => {
          const map = {
            Approved:
              '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-success-soft text-success text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-success"></span>Approved</span>',
            Pending:
              '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#f59e0b]/12 text-[#b45309] text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-[#f59e0b]"></span>Pending</span>',
            Rejected:
              '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-danger-soft text-danger text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-danger"></span>Rejected</span>',
          };
          return map[s] || s;
        };

        const requests = [
          ["Annual Leave", "Apr 22, 2025", "Apr 25, 2025", "4", "Approved"],
          ["Sick Leave", "Apr 10, 2025", "Apr 11, 2025", "2", "Approved"],
          ["Casual Leave", "May 02, 2025", "May 02, 2025", "1", "Pending"],
          ["Annual Leave", "May 19, 2025", "May 23, 2025", "5", "Pending"],
          ["Casual Leave", "Mar 14, 2025", "Mar 14, 2025", "1", "Rejected"],
        ];
        const reqBody = document.querySelector('[data-rows="requests"]');
        if (reqBody) {
          reqBody.innerHTML = requests
            .map(
              (r, i) => `
              <tr class="${i < requests.length - 1 ? "border-b border-border-subtle " : ""}hover:bg-subtle/40 transition-colors">
                <td class="py-3 px-4 font-medium text-heading whitespace-nowrap">${r[0]}</td>
                <td class="py-3 px-2 text-text-secondary whitespace-nowrap">${r[1]}</td>
                <td class="py-3 px-2 text-text-secondary whitespace-nowrap">${r[2]}</td>
                <td class="py-3 px-2 text-text-secondary">${r[3]}</td>
                <td class="py-3 px-2">${statusPill(r[4])}</td>
                <td class="py-3 px-2 pr-4 text-right">
                  <button class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-muted hover:bg-subtle hover:text-danger transition-colors" aria-label="Cancel request"><i class="ph ph-x-circle"></i></button>
                </td>
              </tr>`,
            )
            .join("");
        }

        const approvals = [
          [
            "Jelin Jack",
            "user5.png",
            "Sick Leave",
            "Apr 18 – Apr 19, 2025",
            "2",
          ],
          [
            "Anwar Hussain",
            "user4.png",
            "Annual Leave",
            "Apr 28 – May 02, 2025",
            "5",
          ],
          ["Mim Khan", "user9.png", "Casual Leave", "Apr 21, 2025", "1"],
          [
            "Herry Kane",
            "user6.png",
            "Annual Leave",
            "May 05 – May 09, 2025",
            "5",
          ],
        ];
        const apprBody = document.querySelector('[data-rows="approvals"]');
        if (apprBody) {
          apprBody.innerHTML = approvals
            .map(
              (r, i) => `
              <tr class="${i < approvals.length - 1 ? "border-b border-border-subtle " : ""}hover:bg-subtle/40 transition-colors">
                <td class="py-3 px-4">
                  <span class="inline-flex items-center gap-2 whitespace-nowrap">
                    <img src="./assets/images/${r[1]}" alt="" class="w-8 h-8 rounded-full object-cover" />
                    <span class="font-medium text-heading">${r[0]}</span>
                  </span>
                </td>
                <td class="py-3 px-2 text-text-secondary whitespace-nowrap">${r[2]}</td>
                <td class="py-3 px-2 text-text-secondary whitespace-nowrap">${r[3]}</td>
                <td class="py-3 px-2 text-text-secondary">${r[4]}</td>
                <td class="py-3 px-2 pr-4">
                  <div class="flex items-center justify-end gap-2">
                    <button class="inline-flex items-center gap-1 h-8 px-2.5 rounded-lg bg-success-soft text-success text-xs font-medium hover:opacity-90 transition-opacity"><i class="ph ph-check"></i>Approve</button>
                    <button class="inline-flex items-center gap-1 h-8 px-2.5 rounded-lg bg-danger-soft text-danger text-xs font-medium hover:opacity-90 transition-opacity"><i class="ph ph-x"></i>Reject</button>
                  </div>
                </td>
              </tr>`,
            )
            .join("");
        }
      })();
