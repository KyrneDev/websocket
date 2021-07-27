/*
 * This file is part of Flarum.
 *
 * (c) Toby Zerner <toby.zerner@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import DashboardWidget from 'flarum/components/DashboardWidget';
import humanTime from 'flarum/utils/humanTime';

import {Chart} from 'frappe-charts/dist/frappe-charts.esm.js';

export default class StatisticsWidget extends DashboardWidget {

  className() {
    return 'WebsocketStatisticsWidget';
  }

  content() {

    return (
      <div className="WebsocketStatisticsWidget-table">

        <div className="WebsocketStatisticsWidget-chart" oncreate={this.drawChart.bind(this)}
             onupdate={this.drawChart.bind(this)}/>
      </div>
    );
  }

  drawChart(vnode) {

    const stats = app.data.websocket_statistics;

    if (!this.chart) {
      this.chart = new Chart(vnode.dom, {
        data: {
          labels: stats.peak_connections.x.map(date => {
            return humanTime(new Date(date + ' UTC'));
          }),
          datasets: [
            {
              name: "Null",
              values: stats.null,
              chartType: 'bar'
            },
            {
              name: "Peak Connections",
              values: stats.peak_connections.y,
              chartType: 'bar'
            },
            {
              name: "Messages Count",
              values: stats.websocket_messages_count.y,
              chartType: 'bar'
            },
          ]
        },
        title: app.translator.trans('kyrne-websocket.admin.chart.title'),
        type: 'bar',
        height: 300,
        colors: [app.forum.attribute('themePrimaryColor'), app.forum.attribute('themeSecondaryColor')]
      });
    }
  }
}
