(function () {
        const departments = [
          [
            "Engineering",
            "ph-code",
            "Anwar Hussain",
            "user4.png",
            86,
            5,
            "Active",
          ],
          ["Design", "ph-pen-nib", "Jelin Jack", "user5.png", 24, 2, "Active"],
          [
            "Marketing",
            "ph-megaphone",
            "James Helin",
            "user2.png",
            31,
            1,
            "Active",
          ],
          [
            "Human Resources",
            "ph-users-three",
            "Salina Roy",
            "user9.png",
            12,
            0,
            "Active",
          ],
          [
            "Finance",
            "ph-currency-dollar",
            "Tahsan Khan",
            "user1.png",
            18,
            1,
            "Active",
          ],
          ["Sales", "ph-handshake", "Herry Kane", "user6.png", 42, 3, "Active"],
          ["Operations", "ph-gear", "Mim Khan", "user8.png", 27, 0, "Inactive"],
          ["Support", "ph-headset", "Jenson Roy", "user7.png", 19, 0, "Active"],
        ];
        const designations = [
          ["Senior Software Engineer", "Engineering", "L4", 22],
          ["Frontend Developer", "Engineering", "L3", 18],
          ["Product Designer", "Design", "L3", 9],
          ["UI/UX Designer", "Design", "L2", 11],
          ["SEO Specialist", "Marketing", "L2", 7],
          ["HR Manager", "Human Resources", "L4", 3],
          ["Accountant", "Finance", "L3", 6],
          ["Sales Executive", "Sales", "L2", 20],
        ];

        const statusPill = (s) =>
          s === "Active"
            ? '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-success-soft text-success text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-success"></span>Active</span>'
            : '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#f59e0b]/12 text-[#b45309] text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-[#f59e0b]"></span>Inactive</span>';

        const actions = `
          <div class="flex items-center justify-end gap-1">
            <button class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle hover:text-primary transition-colors" aria-label="Edit"><i class="ph ph-pencil-simple"></i></button>
            <button class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle hover:text-danger transition-colors" aria-label="Delete"><i class="ph ph-trash"></i></button>
          </div>`;

        const depBody = document.querySelector('[data-rows="departments"]');
        if (depBody) {
          depBody.innerHTML = departments
            .map(
              (d, i) => `
              <tr class="${i < departments.length - 1 ? "border-b border-border-subtle " : ""}hover:bg-subtle/40 transition-colors">
                <td class="py-3 px-4">
                  <span class="inline-flex items-center gap-2.5 whitespace-nowrap">
                    <span class="w-9 h-9 rounded-lg bg-primary-soft text-primary flex items-center justify-center flex-shrink-0"><i class="ph ${d[1]}"></i></span>
                    <span class="font-medium text-heading">${d[0]}</span>
                  </span>
                </td>
                <td class="py-3 px-2">
                  <span class="inline-flex items-center gap-2 whitespace-nowrap">
                    <img src="./assets/images/${d[3]}" alt="" class="w-7 h-7 rounded-full object-cover" />
                    <span class="text-text-secondary">${d[2]}</span>
                  </span>
                </td>
                <td class="py-3 px-2 text-text-secondary">${d[4]}</td>
                <td class="py-3 px-2 text-text-secondary">${d[5]}</td>
                <td class="py-3 px-2">${statusPill(d[6])}</td>
                <td class="py-3 px-2 pr-4">${actions}</td>
              </tr>`,
            )
            .join("");
        }

        const desBody = document.querySelector('[data-rows="designations"]');
        if (desBody) {
          desBody.innerHTML = designations
            .map(
              (d, i) => `
              <tr class="${i < designations.length - 1 ? "border-b border-border-subtle " : ""}hover:bg-subtle/40 transition-colors">
                <td class="py-3 px-4 font-medium text-heading whitespace-nowrap">${d[0]}</td>
                <td class="py-3 px-2 text-text-secondary whitespace-nowrap">${d[1]}</td>
                <td class="py-3 px-2"><span class="inline-flex items-center px-2 py-0.5 rounded-full bg-subtle text-text-secondary text-xs font-medium">${d[2]}</span></td>
                <td class="py-3 px-2 text-text-secondary">${d[3]}</td>
                <td class="py-3 px-2 pr-4">${actions}</td>
              </tr>`,
            )
            .join("");
        }
      })();
