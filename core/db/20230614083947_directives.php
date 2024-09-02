<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;
use Phinx\Migration\AbstractMigration;
use Sterc\CSP\Models\Directive;
use Sterc\CSP\Models\Group;

final class Directives extends AbstractMigration
{
    public function up(): void
    {
        $schema = Manager::schema();
        $schema->create(
            'csp_groups',
            static function (Blueprint $table) {
                $table->id();
                $table->string('key');
                $table->string('title');
                $table->text('description')->nullable();
                $table->boolean('active')->default(false);
            }
        );

        $schema->create(
            'csp_directives',
            static function (Blueprint $table) {
                $table->foreignId('group_id')
                    ->constrained('csp_groups')->cascadeOnDelete();
                $table->string('key')->primary();
                $table->json('value')->nullable();
                $table->text('description')->nullable();
                $table->boolean('active')->default(false);
            }
        );

        $this->fillTable();
    }

    protected function fillTable(): void
    {
        $items = [
            [
                'key' => 'fetch',
                'title' => 'Fetch directives',
                'description' => 'Fetch directives control the locations from which certain resource types may be loaded.',
                'directives' => [
                    'child-src' => 'Defines the valid sources for web workers and nested browsing contexts loaded using elements such as <frame> and <iframe>.',
                    'connect-src' => 'Restricts the URLs which can be loaded using script interfaces.',
                    'default-src' => 'Serves as a fallback for the other fetch directives.',
                    'font-src' => 'Specifies valid sources for fonts loaded using @font-face.',
                    'frame-src' => 'Specifies valid sources for nested browsing contexts loading using elements such as <frame> and <iframe>.',
                    'img-src' => 'Specifies valid sources of images and favicons.',
                    'manifest-src' => 'Specifies valid sources of application manifest files.',
                    'media-src' => 'Specifies valid sources for loading media using the <audio> , <video> and <track> elements. ',
                    'object-src' => 'Specifies valid sources for the <object> and <embed> elements.',
                    'prefetch-src' => 'Deprecated. Specifies valid sources to be prefetched or prerendered.',
                    'script-src' => 'Specifies valid sources for JavaScript and WebAssembly resources.',
                    'script-src-elem' => 'Specifies valid sources for JavaScript <script> elements.',
                    'script-src-attr' => 'Specifies valid sources for JavaScript inline event handlers.',
                    'style-src' => 'Specifies valid sources for stylesheets.',
                    'style-src-elem' => 'Specifies valid sources for stylesheets <style> elements and <link> elements with rel="stylesheet". ',
                    'style-src-attr' => 'Specifies valid sources for inline styles applied to individual DOM elements.',
                    'worker-src' => 'Specifies valid sources for Worker, SharedWorker, or ServiceWorker scripts.',
                ],
            ],
            [
                'key' => 'document',
                'title' => 'Document directives',
                'description' => 'Document directives govern the properties of a document or worker environment to which a policy applies.',
                'directives' => [
                    'base-uri' => 'Restricts the URLs which can be used in a document\'s <base> element.',
                    'sandbox' => 'Enables a sandbox for the requested resource similar to the <iframe> sandbox attribute. ',
                ],
            ],
            'navigation' => [
                'key' => 'navigation',
                'title' => 'Navigation directives',
                'description' => 'Navigation directives govern to which locations a user can navigate or submit a form, for example.',
                'directives' => [
                    'form-action' => 'Restricts the URLs which can be used as the target of a form submissions from a given context.',
                    'frame-ancestors' => 'Specifies valid parents that may embed a page using <frame>, <iframe>, <object>, or <embed>.',
                    'navigate-to' => 'Restricts the URLs to which a document can initiate navigation by any means, including <form> (if form-action is not specified), <a>, window.location, window.open, etc.',
                ],
            ],
            [
                'key' => 'reporting',
                'title' => 'Reporting directives',
                'description' => 'Reporting directives control the reporting process of CSP violations. See also the Content-Security-Policy-Report-Only header.',
                'directives' => [
                    'report-uri' => 'Deprecated. Instructs the user agent to report attempts to violate the Content Security Policy. These violation reports consist of JSON documents sent via an HTTP POST request to the specified URI.',
                    'report-to' => 'Fires a SecurityPolicyViolationEvent',
                ],
            ],
            [
                'key' => 'other',
                'title' => 'Other directives',
                'description' => 'Enforces Trusted Types at the DOM XSS injection sinks.',
                'directives' => [
                    'require-trusted-types-for' => 'Enforces Trusted Types at the DOM XSS injection sinks.',
                    'trusted-types' => 'Used to specify an allowlist of Trusted Types policies. Trusted Types allows applications to lock down DOM XSS injection sinks to only accept non-spoofable, typed values in place of strings. ',
                    'upgrade-insecure-requests' => 'Instructs user agents to treat all of a site\'s insecure URLs (those served over HTTP) as though they have been replaced with secure URLs (those served over HTTPS). This directive is intended for websites with large numbers of insecure legacy URLs that need to be rewritten. ',
                ],
            ],
        ];

        foreach ($items as $data) {
            $group = new Group($data);
            $group->save();

            foreach ($data['directives'] as $key => $description) {
                Directive::query()->insert([
                    'group_id' => $group->id,
                    'key' => $key,
                    'description' => $description,
                ]);
            }
        }
    }

    public function down(): void
    {
        $schema = Manager::schema();
        $schema->drop('csp_directives');
        $schema->drop('csp_groups');
    }
}
