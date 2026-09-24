(function () {
        const data = {
          additions: [
            [
              "Leave Balance Amount",
              "Monthly Remuneration",
              "$7,758",
              "Apr 16, 2025",
              "$40,000",
            ],
            [
              "Arrears of Salary",
              "Additional Remuneration",
              "$9,353",
              "Apr 17, 2025",
              "$50,000",
            ],
            [
              "Gratuity",
              "Monthly Remuneration",
              "$8,575",
              "Apr 18, 2025",
              "$60,000",
            ],
            [
              "Remaining Leave",
              "Additional Remuneration",
              "$2,447",
              "Apr 19, 2025",
              "$30,000",
            ],
            [
              "Leave Entitlement Left",
              "Monthly Remuneration",
              "$2,435",
              "Apr 20, 2025",
              "$30,000",
            ],
            [
              "Unused Leave Balance",
              "Monthly Remuneration",
              "$3,435",
              "Apr 21, 2025",
              "$70,000",
            ],
            [
              "Remaining Time Off",
              "Monthly Remuneration",
              "$4,435",
              "Apr 22, 2025",
              "$80,000",
            ],
            [
              "Vacation Balance",
              "Monthly Remuneration",
              "$7,435",
              "Apr 23, 2025",
              "$90,000",
            ],
          ],
          overtime: [
            [
              "Weekend Shift",
              "Overtime Pay",
              "$1,250",
              "Apr 16, 2025",
              "$40,000",
            ],
            ["Night Shift", "Overtime Pay", "$980", "Apr 17, 2025", "$50,000"],
            [
              "Holiday Duty",
              "Overtime Pay",
              "$1,540",
              "Apr 18, 2025",
              "$60,000",
            ],
            ["Extra Hours", "Overtime Pay", "$720", "Apr 19, 2025", "$30,000"],
            [
              "On-call Support",
              "Overtime Pay",
              "$640",
              "Apr 20, 2025",
              "$30,000",
            ],
          ],
          deductions: [
            [
              "Provident Fund",
              "Statutory Deduction",
              "$2,100",
              "Apr 16, 2025",
              "$40,000",
            ],
            [
              "Income Tax (TDS)",
              "Statutory Deduction",
              "$3,400",
              "Apr 17, 2025",
              "$50,000",
            ],
            [
              "Health Insurance",
              "Benefit Deduction",
              "$560",
              "Apr 18, 2025",
              "$60,000",
            ],
            ["Loan Repayment", "Recovery", "$1,200", "Apr 19, 2025", "$30,000"],
            ["Late Penalty", "Recovery", "$150", "Apr 20, 2025", "$30,000"],
          ],
        };
        const rowHtml = (r, last) => `
          <tr class="${last ? "" : "border-b border-border-subtle "}hover:bg-subtle/40 transition-colors">
            <td class="py-3 px-4"><input type="checkbox" class="accent-primary" /></td>
            <td class="py-3 px-2 font-medium text-heading whitespace-nowrap">${r[0]}</td>
            <td class="py-3 px-2 text-text-secondary whitespace-nowrap">${r[1]}</td>
            <td class="py-3 px-2 font-semibold text-heading whitespace-nowrap">${r[2]}</td>
            <td class="py-3 px-2 text-text-secondary whitespace-nowrap">${r[3]}</td>
            <td class="py-3 px-2 text-text-secondary whitespace-nowrap">${r[4]}</td>
            <td class="py-3 px-2 pr-4">
              <div class="flex items-center justify-end gap-1">
                <button class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle hover:text-primary transition-colors" aria-label="Edit"><i class="ph ph-pencil-simple"></i></button>
                <button class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle hover:text-danger transition-colors" aria-label="Delete"><i class="ph ph-trash"></i></button>
              </div>
            </td>
          </tr>`;
        Object.keys(data).forEach((key) => {
          const tbody = document.querySelector(`[data-rows="${key}"]`);
          if (!tbody) return;
          tbody.innerHTML = data[key]
            .map((r, i) => rowHtml(r, i === data[key].length - 1))
            .join("");
        });
      })();
