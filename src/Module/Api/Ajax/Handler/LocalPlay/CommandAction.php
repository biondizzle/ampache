<?php
/*
 * vim:set softtabstop=4 shiftwidth=4 expandtab:
 *
 * LICENSE: GNU Affero General Public License, version 3 (AGPL-3.0-or-later)
 * Copyright 2001 - 2020 Ampache.org
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

declare(strict_types=0);

namespace Ampache\Module\Api\Ajax\Handler\LocalPlay;

use Ampache\Config\AmpConfig;
use Ampache\Module\Api\Ajax\Handler\ActionInterface;
use Ampache\Module\Authorization\Access;
use Ampache\Module\Playback\Localplay\LocalPlay;
use Ampache\Module\Util\Ui;
use Ampache\Repository\Model\Browse;
use Ampache\Repository\Model\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class CommandAction implements ActionInterface
{
    public function handle(
        ServerRequestInterface $request,
        ResponseInterface $response,
        User $user
    ): array {
        $results = [];

        // Make sure they are allowed to do this
        if (!Access::check('localplay', 50)) {
            debug_event('localplay.ajax', 'Attempted to control Localplay without sufficient access', 1);

            return $results;
        }

        $localplay = new LocalPlay(AmpConfig::get('localplay_controller'));
        $localplay->connect();

        // Switch on valid commands
        switch ($_REQUEST['command']) {
            case 'refresh':
                ob_start();
                $objects = $localplay->get();
                require_once Ui::find_template('show_localplay_status.inc.php');
                $results['localplay_status'] = ob_get_contents();
                ob_end_clean();
                break;
            case 'prev':
            case 'next':
            case 'stop':
            case 'play':
            case 'pause':
                $command = scrub_in($_REQUEST['command']);
                $localplay->$command();
                break;
            case 'volume_up':
            case 'volume_down':
            case 'volume_mute':
                $command = scrub_in($_REQUEST['command']);
                $localplay->$command();

                // We actually want to refresh something here
                ob_start();
                $objects = $localplay->get();
                require_once Ui::find_template('show_localplay_status.inc.php');
                $results['localplay_status'] = ob_get_contents();
                ob_end_clean();
                break;
            case 'delete_all':
                $localplay->delete_all();
                ob_start();
                $browse = new Browse();
                $browse->set_type('playlist_localplay');
                $browse->set_static_content(true);
                $browse->save_objects(array());
                $browse->show_objects(array());
                $browse->store();
                $results[$browse->get_content_div()] = ob_get_contents();
                ob_end_clean();
                break;
            case 'skip':
                $localplay->skip((int) filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
                $objects = $localplay->get();
                ob_start();
                $browse = new Browse();
                $browse->set_type('playlist_localplay');
                $browse->set_static_content(true);
                $browse->save_objects($objects);
                $browse->show_objects($objects);
                $browse->store();
                $results[$browse->get_content_div()] = ob_get_contents();
                ob_end_clean();
                break;
            default:
                break;
        } // end whitelist

        return $results;
    }
}
