(function () {
        // ===== Weekly overview (ApexCharts stacked column) =====
        const chartEl = document.querySelector("[data-attendance-chart]");
        if (chartEl && window.ApexCharts) {
          const css = (name) =>
            getComputedStyle(document.documentElement)
              .getPropertyValue(name)
              .trim();

          const days = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
          const present = [92, 88, 95, 90, 84, 60, 30];
          const absent = present.map((p) => 100 - p);
          let chart;

          const buildOptions = () => {
            const isDark = document.documentElement.classList.contains("dark");
            const primary = css("--color-primary") || "#05a1f6";
            const track = css("--color-subtle") || "#eef0f3";
            const muted = css("--color-muted") || "#6b7280";
            const gridc = css("--color-border-subtle") || "#eef0f3";
            return {
              chart: {
                type: "bar",
                height: 300,
                stacked: true,
                stackType: "100%",
                fontFamily: "Inter, sans-serif",
                foreColor: muted,
                toolbar: { show: false },
                animations: { speed: 400 },
              },
              series: [
                { name: "Present", data: present },
                { name: "Absent", data: absent },
              ],
              colors: [primary, track],
              plotOptions: {
                bar: {
                  columnWidth: "45%",
                  borderRadius: 6,
                  borderRadiusApplication: "end",
                },
              },
              dataLabels: { enabled: false },
              legend: {
                position: "top",
                horizontalAlign: "right",
                markers: { radius: 4 },
                fontSize: "12px",
              },
              grid: {
                borderColor: gridc,
                strokeDashArray: 4,
                padding: { left: 4, right: 4 },
              },
              xaxis: {
                categories: days,
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { fontSize: "11px" } },
              },
              yaxis: {
                labels: {
                  formatter: (v) => v + "%",
                  style: { fontSize: "11px" },
                },
              },
              tooltip: {
                theme: isDark ? "dark" : "light",
                y: { formatter: (v) => v + "%" },
              },
              states: { hover: { filter: { type: "darken", value: 0.9 } } },
            };
          };

          const render = () => {
            if (chart) chart.destroy();
            chart = new ApexCharts(chartEl, buildOptions());
            chart.render();
          };
          render();

          // Re-render with new token colors when the theme toggles
          window.addEventListener("hr:theme-change", render);
        }

        // Attendance log rows
        const rows = [
          [
            "Tahsan Khan",
            "user1.png",
            "Finance",
            "08:58 AM",
            "06:02 PM",
            "9h 04m",
            "Present",
          ],
          [
            "Jelin Jack",
            "user5.png",
            "Design",
            "09:14 AM",
            "06:10 PM",
            "8h 56m",
            "Late",
          ],
          ["Helina Wiliy", "user2.png", "Marketing", "—", "—", "—", "Absent"],
          [
            "Anwar Hussain",
            "user4.png",
            "Engineering",
            "08:45 AM",
            "05:50 PM",
            "9h 05m",
            "Present",
          ],
          [
            "Nabila Khan",
            "user8.png",
            "Human Resources",
            "09:02 AM",
            "06:00 PM",
            "8h 58m",
            "WFH",
          ],
          [
            "Jaman Khan",
            "user6.png",
            "Engineering",
            "09:20 AM",
            "06:15 PM",
            "8h 55m",
            "Late",
          ],
          [
            "Mim Khan",
            "user9.png",
            "Marketing",
            "08:50 AM",
            "05:58 PM",
            "9h 08m",
            "Present",
          ],
          ["Jenson Roy", "user7.png", "Support", "—", "—", "—", "Absent"],
        ];

        const statusPill = (s) => {
          const map = {
            Present:
              '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-success-soft text-success text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-success"></span>Present</span>',
            Late: '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#f59e0b]/12 text-[#b45309] text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-[#f59e0b]"></span>Late</span>',
            Absent:
              '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-danger-soft text-danger text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-danger"></span>Absent</span>',
            WFH: '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-primary-soft text-primary text-xs font-medium"><span class="w-1.5 h-1.5 rounded-full bg-primary"></span>WFH</span>',
          };
          return map[s] || s;
        };

        const tbody = document.querySelector('[data-rows="attendance"]');
        if (tbody) {
          tbody.innerHTML = rows
            .map(
              (r, i) => `
              <tr class="${i < rows.length - 1 ? "border-b border-border-subtle " : ""}hover:bg-subtle/40 transition-colors">
                <td class="py-3 px-4">
                  <span class="inline-flex items-center gap-2 whitespace-nowrap">
                    <img src="./assets/images/${r[1]}" alt="" class="w-8 h-8 rounded-full object-cover" />
                    <span class="font-medium text-heading">${r[0]}</span>
                  </span>
                </td>
                <td class="py-3 px-2 text-text-secondary whitespace-nowrap">${r[2]}</td>
                <td class="py-3 px-2 text-text-secondary">${r[3]}</td>
                <td class="py-3 px-2 text-text-secondary">${r[4]}</td>
                <td class="py-3 px-2 font-medium text-heading">${r[5]}</td>
                <td class="py-3 px-2">${statusPill(r[6])}</td>
              </tr>`,
            )
            .join("");
        }
      })();
