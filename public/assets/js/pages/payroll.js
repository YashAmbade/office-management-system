(function () {
        const rows = [
          [
            "EMP-0001",
            "Tahsan Khan",
            "Finance",
            "user1.png",
            "tahsan.khan@gmail.com",
            "(324) 536 75267",
            "Apr 16, 2025",
            "$40,000",
          ],
          [
            "EMP-0002",
            "Jelin Jack",
            "Developer",
            "user5.png",
            "jelin.jack@gmail.com",
            "(324) 536 75267",
            "Apr 17, 2025",
            "$50,000",
          ],
          [
            "EMP-0003",
            "Helina Wiliy",
            "Executive Officer",
            "user2.png",
            "helina.wiliy@gmail.com",
            "(324) 536 75267",
            "Apr 18, 2025",
            "$60,000",
          ],
          [
            "EMP-0004",
            "Anwar Hussen",
            "Manager",
            "user4.png",
            "anwar.hussen@gmail.com",
            "(324) 536 75267",
            "Apr 19, 2025",
            "$20,000",
          ],
          [
            "EMP-0005",
            "Nabila Khan",
            "Manager",
            "user8.png",
            "nabila.khan@gmail.com",
            "(324) 536 75267",
            "Apr 20, 2025",
            "$30,000",
          ],
          [
            "EMP-0006",
            "Jaman Khan",
            "Developer",
            "user6.png",
            "jaman.khan@gmail.com",
            "(324) 536 75267",
            "Apr 21, 2025",
            "$70,000",
          ],
          [
            "EMP-0007",
            "Mim Khan",
            "Executive Officer",
            "user9.png",
            "mim.khan@gmail.com",
            "(324) 536 75267",
            "Apr 22, 2025",
            "$30,000",
          ],
          [
            "EMP-0008",
            "Baly Helen",
            "Finance",
            "user7.png",
            "baly.helen@gmail.com",
            "(324) 536 75267",
            "Apr 23, 2025",
            "$90,000",
          ],
          [
            "EMP-0009",
            "Keliana Mary",
            "Finance",
            "user3.png",
            "keliana.mary@gmail.com",
            "(324) 536 75267",
            "Apr 24, 2025",
            "$60,000",
          ],
          [
            "EMP-0010",
            "James Helin",
            "Manager",
            "user1.png",
            "james.helin@gmail.com",
            "(324) 536 75267",
            "Apr 25, 2025",
            "$40,000",
          ],
        ];
        const tbody = document.querySelector("[data-payroll-rows]");
        if (!tbody) return;
        tbody.innerHTML = rows
          .map(
            (r, i) => `
            <tr class="${i < rows.length - 1 ? "border-b border-border-subtle " : ""}hover:bg-subtle/40 transition-colors">
              <td class="py-3 px-4"><input type="checkbox" class="accent-primary" /></td>
              <td class="py-3 px-2 font-medium text-text-secondary whitespace-nowrap">${r[0]}</td>
              <td class="py-3 px-2">
                <span class="inline-flex items-center gap-2 whitespace-nowrap">
                  <img src="./assets/images/${r[3]}" alt="" class="w-8 h-8 rounded-full object-cover" />
                  <span class="leading-tight">
                    <span class="block font-medium text-heading">${r[1]}</span>
                    <span class="block text-[11px] text-muted">${r[2]}</span>
                  </span>
                </span>
              </td>
              <td class="py-3 px-2 text-muted whitespace-nowrap">${r[4]}</td>
              <td class="py-3 px-2 text-text-secondary whitespace-nowrap">${r[5]}</td>
              <td class="py-3 px-2">
                <span class="inline-flex items-center gap-1 text-text-secondary whitespace-nowrap">${r[2]}</span>
              </td>
              <td class="py-3 px-2 text-text-secondary whitespace-nowrap">${r[6]}</td>
              <td class="py-3 px-2 font-semibold text-heading whitespace-nowrap">${r[7]}</td>
              <td class="py-3 px-2 pr-4">
                <div class="flex items-center justify-end gap-1">
                  <button class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle hover:text-primary transition-colors" aria-label="Edit"><i class="ph ph-pencil-simple"></i></button>
                  <button class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle hover:text-danger transition-colors" aria-label="Delete"><i class="ph ph-trash"></i></button>
                </div>
              </td>
            </tr>`,
          )
          .join("");
      })();
